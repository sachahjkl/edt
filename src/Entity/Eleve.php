<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 */
class Eleve extends Personne
{

    public function getRoles(): array
    {
        $roles = $this->getRoles();
        $roles[] = 'ROLE_ELEVE';
        return array_unique($roles);
    }

}
