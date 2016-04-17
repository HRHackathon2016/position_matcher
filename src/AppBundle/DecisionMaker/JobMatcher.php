<?php

namespace AppBundle\DecisionMaker;

use AppBundle\Model\Job;
use AppBundle\Model\User;

class JobMatcher
{
    /**
     * @param User $user
     * @param Job[] $jobs
     * @return array
     */
    public function orderJobs(User $user, $jobs)
    {
        $decisionMaker = new DecisionMaker();
        $acceptedJobs = [];
        foreach ($jobs as $job) {
            if ($decisionMaker->decide($user, $job->getProperties())) {
                $acceptedJobs[$job->getId()] = $job;
            }
        }

        return $acceptedJobs;
    }
}
