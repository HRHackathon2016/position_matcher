<?php
namespace AppBundle\Factory;

use Doctrine\MongoDB\Connection;
use Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;

class DocumentManagementFactory
{
    public static function build()
    {
        $config = new Configuration();
        $config->setProxyDir(__DIR__ . '/../Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/../Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setMetadataDriverImpl(
            AnnotationDriver::create(__DIR__ . '/../Model')
        );

        AnnotationDriver::registerAnnotationClasses();
        return DocumentManager::create(new Connection(), $config);
    }
}
