<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\ProfesseurProfileType;
use App\Form\ResetPasswordType;
use App\Repository\CoursRepository;
use App\Repository\ExamenRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/prof", name="prof")
 * @IsGranted("ROLE_PROF")
 */
class ProfesseurController extends AbstractController
{

    /**
     * @Route("/settings", name="_settings", methods={"GET","POST"})
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

    /**
     * @Route("/cours", name="_cours")
     */
    public function indexCours()
    {
        $prof = $this->getUser();
        $cours = $prof->getCours()->filter(function ($cours) {
            return $cours->getStatut();
        });

        return $this->render('professeur/cours/index.html.twig', [
                "cours" => $cours
            ]
        );
    }


    /**
     * @Route("/cours/edit/{id}", name="_cours_edit")
     * @param Response $response
     * @param CoursRepository $coursRepository
     */
    public function editCours(Request $request, CoursRepository $coursRepository)
    {
        return $this->redirectToRoute("prof_cours");
    }

    /**
     * @Route("/exam/edit/{id}", name="_exam_edit")
     * @param Response $response
     * @param ExamenRepository $examenRepository
     */
    public function editExam(Request $request, ExamenRepository $examenRepository)
    {
        return $this->redirectToRoute("prof_cours");
    }

}
