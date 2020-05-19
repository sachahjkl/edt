<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ProfesseurRepository::class)
 */
class Professeur extends Personne
{

}
