<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Form\EleveProfileType;
use App\Form\EleveType;
use App\Form\ResetPasswordType;
use App\Repository\EleveRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EleveController extends AbstractController
{
    /**
     * @Route("/personne/settings", name="eleve_settings", methods={"GET","POST"})/
     * @IsGranted("ROLE_ELEVE")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $eleve = $this->getUser();
        $form = $this->createForm(EleveProfileType::class, $eleve);
        $form->handleRequest($request);
        $form_rp = $this->createForm(ResetPasswordType::class, $eleve);
        $form_rp->handleRequest($request);
        if ($form_rp->isSubmitted() && $form_rp->isValid()) {
            $oldPassword = $form_rp->get('oldPassword')->getData();
            $newEncodedPassword = $encoder->encodePassword($eleve, $eleve->getPlainPassword());
            $eleve->setPassword($newEncodedPassword);
            $this->getDoctrine()->getManager()->persist($eleve);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Votre mot de passe à bien été changé !');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('eleve/edit.html.twig', [
            'eleve' => $eleve,
            'form' => $form->createView(),
            'form_rp' => $form_rp->createView()
        ]);
    }
}
