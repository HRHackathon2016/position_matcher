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
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
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

        if (empty($url) || empty($email))
        {
            return new Response(400, 'url and email must not be empty.');
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
                ->setEmail($email);
        }

        $document_manager->persist($user);
        $document_manager->flush();

        //$user = $document_manager->getRepository('AppBundle\Model\User')->findOneBy(['email' => $email]);

        $matcher = $this->get('matcher_service');

        $jobSuggestions = $matcher->predict($user);
        return new JsonResponse(['suggestions' => $jobSuggestions]);
    }
}
