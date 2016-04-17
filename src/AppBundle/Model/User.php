<?php

namespace AppBundle\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $last_name;

    /** @ODM\Field(type="string") */
    private $first_name;

    /** @ODM\Field(type="string") */
    private $email;

    /** @ODM\ReferenceMany(targetDocument="AppBundle\Model\User\Job", cascade="all") */
    private $jobs;

    /** @ODM\Field(type="hash") */
    private $languages;

    /** @ODM\Field(type="hash") */
    private $skills;

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     * @return User
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     * @return User
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param mixed $jobs
     * @return User
     */
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param string[] $languages
     * @return User
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * @param string[] $skills
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
    }
}
