<?php

namespace AppBundle\Controller;

use AppBundle\Model\User;
use AppBundle\Model\User\Job;

use AppBundle\Scraper\Scraper;
use AppBundle\Service\Matcher;
use Doctrine\ODM\MongoDB\DocumentManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/feedback", name="feedback")
     */
    public function feedbackAction(Request $request)
    {
        $email = $request->query->get('email', null);
        $jobId = $request->query->get('jobId', null);
        if (empty($email) || empty($jobId))
        {
            return new Response('jobId and email must not be empty.', 400);
        }

        $document_manager = $this->get('document_manager');


        $user = $document_manager->getRepository('AppBundle\Model\User')
            ->findOneBy(['email' => $email]);
        if (empty($user))
        {
            return new Response('User does not exist', 404);
        }

        $matcher = $this->get('matcher_service');
        $matcher->learn(array_values($user->getPersonalTrails()), $jobId);
    }

    /**
     * @param Request $request
     * @Route("/user", name="Fetch user")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fetchUserAction(Request $request)
    {
        $document_manager = $this->get('document_manager');
        // todo:
        $url = $request->query->get('url', null);
        $email = $request->query->get('email', null);
        $personalTrails = $request->query->get('trails', []);
        ksort($personalTrails);
        if (empty($url) || empty($email))
        {
            return new Response('url and email must not be empty.', 400);
        }

        $scrapeData = (new Scraper())->getData($url);

        $jobs = array_map(function(\AppBundle\Scraper\Job $job){
            return (new Job())->setCompany($job->getCompanyName())
                ->setTitle($job->getJobTitle())
                ->setDuration($job->getDuration());
        }, $scrapeData['jobs']);

        $usersRepo = $document_manager->getRepository('AppBundle\Model\User');

        $user = $usersRepo->findOneBy(['email' => $email]);
        if (empty($user))
        {
            $user = (new User())
                ->setName($scrapeData['name'])
                ->setLanguages($scrapeData['languages'])
                ->setSkills($scrapeData['skills'])
                ->setJobs($jobs)
                ->setEmail($email)
                ->setPersonalTrails($personalTrails);
        }

        $document_manager->persist($user);
        $document_manager->flush();

        //$user = $document_manager->getRepository('AppBundle\Model\User')->findOneBy(['email' => $email]);

        $matcher = $this->get('matcher_service');

        $jobSuggestions = $matcher->predict($user);
        return new JsonResponse(['suggestions' => $jobSuggestions]);
    }
}
