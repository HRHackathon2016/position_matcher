<?php

namespace AppBundle\Controller;

use AppBundle\Model\User;
use AppBundle\Model\User\Job;

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
        // todo: fetch from LI: $public_user_data = feth_from_linkedin
        $user = (new User())
            ->setFirstName('first')
            ->setLastName('last')
            //->setLanguages(/*lang*/)
            //->setSkills(/*skillset*/)
            ->setEmail('user@example.com');
        $document_manager->persist($user);
        $document_manager->flush();

        return new Response();
    }
}
