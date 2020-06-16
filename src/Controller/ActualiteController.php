<?php

namespace App\Controller;

use App\Entity\Actualite;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actualite")
 */
class ActualiteController extends AbstractController
{

    /**
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     * @Route("/{id}", name="actualite_show", methods={"GET"})
     * @param Actualite $actualite
     * @return Response
     */
    public function show(Actualite $actualite): Response
    {
        return $this->render('actualite/show.html.twig', [
            'actualite' => $actualite,
        ]);
    }


}
