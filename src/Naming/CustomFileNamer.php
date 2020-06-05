<?php

namespace App\Naming;

use Psr\Log\LoggerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 *
 */
class CustomFileNamer implements NamerInterface
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function name($object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);
        $name = $file->getClientOriginalName();
        $owner = $object->getProprietaire();
        $prenom = strtolower($owner->getPrenom());
        $nom = strtolower($owner->getNom());
        return uniqid() . "_" . $nom . "_" . $prenom . "_" . $name ;
    }

}
