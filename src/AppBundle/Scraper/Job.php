<?php

/**
 * Created by PhpStorm.
 * User: mbrea
 * Date: 16/04/16
 * Time: 16:01
 */
namespace AppBundle\Scraper;

class Job extends \ArrayObject
{
    public function __construct($jobTitle, $companyName, $duration){
        parent::__construct(['companyName' => $companyName,'jobTitle' => $jobTitle, 'duration' => $duration], self::ARRAY_AS_PROPS);
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param mixed $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }


}