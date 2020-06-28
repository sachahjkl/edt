<?php

namespace App\Controller;

use App\Form\ProfesseurProfileType;
use App\Form\ResetPasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/professeur")
 */
class ProfesseurController extends AbstractController
{

    /**
     * @Route("/prof/settings", name="prof_settings", methods={"GET","POST"})
     * @IsGranted("ROLE_PROF")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $prof = $this->getUser();
        $form = $this->createForm(ProfesseurProfileType::class, $prof);
        $form->handleRequest($request);
        $form_rp = $this->createForm(ResetPasswordType::class, $prof);
        $form_rp->handleRequest($request);
        if ($form_rp->isSubmitted() && $form_rp->isValid()) {
            $newEncodedPassword = $encoder->encodePassword($prof, $prof->getPlainPassword());
            $prof->setPassword($newEncodedPassword);
            $this->getDoctrine()->getManager()->persist($prof);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Votre mot de passe à bien été changé !');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('eleve/edit.html.twig', [
            'form' => $form->createView(),
            'form_rp' => $form_rp->createView()
        ]);
    }

}
