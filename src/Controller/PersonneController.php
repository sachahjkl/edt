<?php

namespace App\Controller;

use App\Repository\ActualiteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/", name="_root")
     * @param ActualiteRepository $actualiteRepository
     * @return Response
     */
    public function index(ActualiteRepository $actualiteRepository)
    {
        return $this->render('personne/index.html.twig', [
            'title' => "Dashboard",
            'actualites' => $actualiteRepository->findAll()
        ]);
    }
}
