<?php

namespace App\Controller;

use App\Form\EleveProfileType;
use App\Form\ResetPasswordType;
use App\Repository\MatiereRepository;
use App\Repository\NoteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class EleveController
 * @package App\Controller
 * @IsGranted("ROLE_ELEVE")
 */
class EleveController extends AbstractController
{
    /**
     * @Route("/eleve/settings", name="eleve_settings", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $eleve = $this->getUser();
        $form = $this->createForm(EleveProfileType::class, $eleve);
        $form->handleRequest($request);
        $form_rp = $this->createForm(ResetPasswordType::class, $eleve);
        $form_rp->handleRequest($request);
        if ($form_rp->isSubmitted() && $form_rp->isValid()) {
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
            'form' => $form->createView(),
            'form_rp' => $form_rp->createView()
        ]);
    }

    /**
     * @Route("/eleve/notes", name="eleve_notes")
     */
    public function listNotes(NoteRepository $noteRepository, MatiereRepository $matiereRepository): Response
    {
        $eleve = $this->getUser();
        $notes = $eleve->getNotes();
        $moyennes = array();
        $matieres = $matiereRepository->findAll();
        foreach ($matieres as $matiere) {
            $moyenne = $noteRepository->getMoyenne($eleve, $matiere);
            $moyennes[$matiere->getIntitule()] =  $moyenne[0]["1"];
        }

        return $this->render('eleve/notes.html.twig', [
                "moyennes" => $moyennes,
                "notes" => $notes
            ]
        );
    }

    /**
     * @Route("/eleve/note/{id}", name="eleve_note")
     */
    public function note(Request $request, NoteRepository $noteRepository): Response
    {
        $id = $request->get("id");
        $note = $noteRepository->findOneBy([
            "eleve" => $this->getUser(),
            "id" => $id
        ]);

        if ($note) {
            return $this->render("eleve/note.html.twig", [
                "note" => $note
            ]);
        } else {
            return $this->redirectToRoute("eleve_notes");
        }

    }
}
