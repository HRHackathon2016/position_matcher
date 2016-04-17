<?php

namespace AppBundle\Service;

use AppBundle\Model\Job;
use AppBundle\Model\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use GuzzleHttp\Client;

class Matcher
{

    /** @var  string */
    private $url;
    /** @var  DocumentManager */
    private $documentManager;
    /** @var  Client */
    private $lcient;

    public function __construct($url, DocumentManager $documentManager)
    {
        $this->url = rtrim('/ ', $url) . '/';
        $this->documentManager = $documentManager;
        $this->client = new Client();
    }

    public function learn ()
    {
        
    }
    
    public function predict(User $user)
    {
        $jobs = $this->documentManager->getRepository('AppBundle\Model\Job')->findAll();
        // TODO: check properties from DB with users' fields

        $job_data = '';

        $this->client->get(
            $this->url . 'predict?' . \GuzzleHttp\Psr7\build_query(
                [
                    'data' => $job_data,
                ]
            )
        );

        return array_map(
            function(Job $job)
            {
                return $job->getId();
            },
            $jobs
        );
    }
}
