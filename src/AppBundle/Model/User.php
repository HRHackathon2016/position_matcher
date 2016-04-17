<?php

namespace AppBundle\Model;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class User
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $name;

    /** @ODM\Field(type="string") */
    private $email;

    /** @ODM\ReferenceMany(targetDocument="AppBundle\Model\User\Job", cascade="all") */
    private $jobs;

    /** @ODM\Field(type="hash") */
    private $languages;

    /** @ODM\Field(type="hash") */
    private $skills;

    /**
     * @ODM\Field(type="hash)
     */
    private $personalTrails;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * 
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;
        return $this;
    }

    /**
     * @param mixed $personalTrails
     * @return User
     */
    public function setPersonalTrails($personalTrails)
    {
        $this->personalTrails = $personalTrails;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPersonalTrails()
    {
        return $this->personalTrails;
    }
}
