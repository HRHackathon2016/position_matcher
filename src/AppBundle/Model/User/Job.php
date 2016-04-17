<?php

namespace AppBundle\Model\User;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Job
{
    /** @var @ODM\Id */
    private $id;

    /** @ODM\Field(type="string") */
    private $title;

    /** @ODM\Field(type="string") */
    private $company;

    /** @ODM\Field(type="integer") */
    private $duration;
}
