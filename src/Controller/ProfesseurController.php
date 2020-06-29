<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Eleve;
use App\Form\ProfCoursType;
use App\Form\ProfProfileType;
use App\Form\ResetPasswordType;
use App\Repository\AbsenceRepository;
use App\Repository\CoursRepository;
use App\Repository\EleveRepository;
use App\Repository\ExamenRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
        $form = $this->createForm(ProfProfileType::class, $prof);
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
     * @Route("/cours/{id}/edit", name="_cours_edit")
     * @param Request $request
     * @param CoursRepository $coursRepository
     */
    public function editCours(Request $request, CoursRepository $coursRepository)
    {
        $id = $request->get("id");
        $cours = $coursRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);
        if ($cours) {
            $form = $this->createForm(ProfCoursType::class, $cours);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $cours = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($cours);
                $entityManager->flush();
                return $this->redirectToRoute("prof_cours");
            }

            return $this->render("professeur/cours/edit.html.twig", [
                'form' => $form->createView(),
                "cours" => $cours
            ]);
        } else {
            return $this->redirectToRoute("prof_cours");
        }
    }

    /**
     * @Route("/cours/{id}/absences", name="_cours_absences")
     * @param Request $request
     * @param CoursRepository $coursRepository
     * @param AbsenceRepository $absenceRepository
     */
    public function indexCoursAbsences(Request $request, CoursRepository $coursRepository, AbsenceRepository $absenceRepository, EleveRepository $eleveRepository)
    {
        $id = $request->get("id");
        $cours = $coursRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);
        if ($cours) {
            $absences = $absenceRepository->findBy([
                "cours" => $cours
            ]);

            return $this->render("professeur/absence/index.html.twig", [
                    "cours" => $cours,
                    "absences" => $absences,
                    "peut_ajouter" => (count($eleveRepository->getElevesSansAbsences($cours)) != 0)
                ]
            );
        }
        return $this->redirectToRoute("prof_cours");
    }

    /**
     * @Route("/cours/{id}/absence/new", name="_cours_absence_new")
     * @param Request $request
     * @param CoursRepository $coursRepository
     * @param EleveRepository $eleveRepository
     */
    public function newCoursAbsences(Request $request, CoursRepository $coursRepository, EleveRepository $eleveRepository)
    {
        $id = $request->get("id");
        $cours = $coursRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);
        if ($cours) {
            $absence = new Absence();
            $absence->setCours($cours);
            $eleves_sans_absences = $eleveRepository->getElevesSansAbsences($cours);
            if (count($eleves_sans_absences) == 0) {
                return $this->redirectToRoute("prof_cours_absences", ["id" => $id]);
            }
            $form = $this->createFormBuilder($absence)
                ->add("absent", EntityType::class, [
                    'class' => Eleve::class,
                    'choices' => $eleves_sans_absences,
                ])
                ->add("save", SubmitType::class, [
                    "label" => "Enregister l'absence"
                ])->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $absence = $form->getData();
                $entityManager->persist($absence);
                $entityManager->flush();
                return $this->redirectToRoute("prof_cours_absences", ["id" => $id]);

            }

            return $this->render("professeur/absence/new.html.twig", [
                'form' => $form->createView(),
                "cours" => $cours
            ]);
        }
        return $this->redirectToRoute("prof_cours");
    }

    /**
     * @Route("/cours/{id}/absence/{id_abs}/delete", name="_cours_absence_delete")
     * @param Request $request
     */
    public function deleteCoursAbsences(Request $request, CoursRepository $coursRepository, AbsenceRepository $absenceRepository)
    {
        $id = $request->get("id");
        $cours = $coursRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);
        if ($cours) {
            $id_abs = $request->get("id_abs");
            $absence = $absenceRepository->findOneBy([
                "cours" => $cours,
                "id" => $id_abs

            ]);
            if ($absence) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($absence);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute("prof_cours_absences", ["id" => $id]);
    }

    /**
     * @Route("/exam/{id}/notes", name="_exam_notes")
     * @param Response $response
     * @param ExamenRepository $examenRepository
     */
    public
    function indexExamNotes()
    {
        return $this->redirectToRoute("prof_cours");
    }

}
