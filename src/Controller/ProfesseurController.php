<?php

namespace App\Controller;

use App\Entity\Absence;
use App\Entity\Eleve;
use App\Entity\Examen;
use App\Entity\Note;
use App\Form\ProfCoursType;
use App\Form\ProfProfileType;
use App\Form\ResetPasswordType;
use App\Repository\AbsenceRepository;
use App\Repository\CoursRepository;
use App\Repository\EleveRepository;
use App\Repository\ExamenRepository;
use App\Repository\NoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function newCoursAbsence(Request $request, CoursRepository $coursRepository, EleveRepository $eleveRepository)
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
    public function deleteCoursAbsence(Request $request, CoursRepository $coursRepository, AbsenceRepository $absenceRepository)
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
     * @Route("/exam/{id}/note", name="_exam_notes")
     * @param Response $response
     * @param ExamenRepository $examenRepository
     */
    public function indexExamNotes(Request $request, ExamenRepository $examenRepository, NoteRepository $noteRepository, EleveRepository $eleveRepository)
    {
        $id = $request->get("id");
        $examen = $examenRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);

        if ($examen) {
            $notes = $noteRepository->findBy([
                "examen" => $examen
            ]);
            $moyenne = 0;
            if ($notes) {
                foreach ($notes as $note) {
                    $moyenne += $note->getNote();
                }
                $moyenne = $moyenne / count($notes);
            }

            return $this->render("professeur/note/index.html.twig", [
                    "examen" => $examen,
                    "notes" => $notes,
                    "moyenne" => $moyenne,
                    "peut_ajouter" => (count($eleveRepository->getElevesSansNotes($examen)) != 0)
                ]
            );
        }
        return $this->redirectToRoute("prof_cours");
    }

    /**
     * @Route("/exam/{id}/note/new", name="_exam_note_new")
     * @param Request $request
     * @param CoursRepository $coursRepository
     * @param EleveRepository $eleveRepository
     * @return RedirectResponse|Response
     */
    public function newExamNotes(Request $request, ExamenRepository $examenRepository, EleveRepository $eleveRepository)
    {
        $id = $request->get("id");
        $examen = $examenRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);
        if ($examen) {
            $note = new Note();
            $note->setExamen($examen);
            $eleves_sans_absences = $eleveRepository->getElevesSansNotes($examen);
            if (count($eleves_sans_absences) == 0) {
                return $this->redirectToRoute("prof_exam_notes", ["id" => $id]);
            }
            $form = $this->createFormBuilder($note)
                ->add("eleve", EntityType::class, [
                    'class' => Eleve::class,
                    'choices' => $eleves_sans_absences,
                    'label' => "Élève à noter"
                ])
                ->add("note", NumberType::class, [
                    "label" => "Note"
                ])
                ->add("save", SubmitType::class, [
                    "label" => "Enregister la note"
                ])->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $note = $form->getData();
                $entityManager->persist($note);
                $entityManager->flush();
                return $this->redirectToRoute("prof_exam_notes", ["id" => $id]);
            }

            return $this->render("professeur/note/new.html.twig", [
                'form' => $form->createView(),
                "examen" => $examen
            ]);
        }
        return $this->redirectToRoute("prof_cours");
    }

    /**
     * @Route("/exam/{id}/note/{id_note}/delete", name="_exam_note_delete")
     * @param Request $request
     */
    public function deleteExamNote(Request $request, ExamenRepository $examenRepository, NoteRepository $noteRepository)
    {
        $id = $request->get("id");
        $examen = $examenRepository->findOneBy([
            "professeur" => $this->getUser(),
            "id" => $id
        ]);
        if ($examen) {
            $id_note = $request->get("id_note");
            $note = $noteRepository->findOneBy([
                "examen" => $examen,
                "id" => $id_note
            ]);
            if ($note) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($note);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute("prof_exam_notes", ["id" => $id]);
    }

}
