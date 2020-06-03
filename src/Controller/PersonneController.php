<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PersonneController
 * @package App\Controller
 * @Route("/personne",name="personne")
 * @IsGranted("ROLE_PERSONNE")
 */
class PersonneController extends AbstractController
{
    /**
     * @Route("/dashboard", name="_index")
     * @Route("", name="_root")
     */
    public function index()
    {
        return $this->render('personne/index.html.twig', [
            'controller_name' => 'PersonneController',
        ]);
    }
}
