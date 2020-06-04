<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EasyAdminUtilisateurController extends EasyAdminController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Utilisateur $user
     */
    public function persistEntity($user)
    {
        $this->updatePassword($user);
        parent::persistEntity($user);
    }

    /**
     * @param Utilisateur $user
     */
    public function updateEntity($user)
    {
        $this->updatePassword($user);
        parent::updateEntity($user);
    }

    public function updatePassword(Utilisateur $user)
    {
        if (!empty($user->getPlainPassword())) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()));
        }
    }

}