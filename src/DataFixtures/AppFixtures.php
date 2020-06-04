<?php

namespace App\DataFixtures;

use App\Entity\Administrateur;
use App\Entity\Eleve;
use App\Entity\Formation;
use App\Entity\Groupe;
use App\Entity\Matiere;
use App\Entity\Professeur;
use App\Entity\Salle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        /* Administrateurs */

        $admin1 = new Administrateur();
        $admin1->setUsername("admin1")
            ->setPassword($this->passwordEncoder->encodePassword($admin1, "password"));
        $manager->persist($admin1);

        /* Professeurs */
        $prof1 = new Professeur();
        $prof1->setPrenom("Cédric")
            ->setNom("Du Mouza")
            ->setUsername("cedricdumouza")
            ->setPassword($this->passwordEncoder->encodePassword($prof1, "password")) //"password"
            ->setDateNaissance(new DateTime("12 April 1976"));
        $manager->persist($prof1);

        $prof2 = new Professeur();
        $prof2->setPrenom("Faycal")
            ->setNom("Hamdi")
            ->setUsername("fayçalhamdi")
            ->setPassword($this->passwordEncoder->encodePassword($prof2, "password")) //"password"
            ->setDateNaissance(new DateTime("15 May 1978"));
        $manager->persist($prof2);
        /* Formations */

        $formation = new Formation();
        $formation->setDureeAnnees(3)
            ->setIntitule("Ingénieur Système d'information")
            ->setResponsable($prof1);
        $manager->persist($formation);

        /* Groupes */

        $groupe = new Groupe();
        $groupe->setNom("fip1")
            ->setFormation($formation);
        $formation->addGroupe($groupe);
        $manager->persist($groupe);

        $groupe = new Groupe();
        $groupe->setNom("fip2")
            ->setFormation($formation);
        $formation->addGroupe($groupe);
        $manager->persist($groupe);

        $groupe = new Groupe();
        $groupe->setNom("fip3")
            ->setFormation($formation);
        $formation->addGroupe($groupe);
        $manager->persist($groupe);

        /* Eleves */

        $eleve = new Eleve();
        $eleve->setUsername("sachafroment")
            ->setPassword($this->passwordEncoder->encodePassword($eleve, "password")) //"password"
            ->setPrenom("Sacha")
            ->setNom("Froment")
            ->setDateNaissance(new DateTime("25 May 1999"))
            ->setGroupe($groupe);
        $manager->persist($eleve);

        $eleve = new Eleve();
        $eleve->setUsername("sivaajithansivathevan")
            ->setPassword($this->passwordEncoder->encodePassword($eleve, "password")) //"password"
            ->setPrenom("Sivaajithan")
            ->setNom("Sivathevan")
            ->setDateNaissance(new DateTime("8 June 1999"))
            ->setGroupe($groupe);
        $manager->persist($eleve);

        /* Matières */

        $matiere = new Matiere();
        $matiere->setIntitule("Méthodologie des SI")
            ->setResponsable($prof2)
            ->setCreditsECTS(50);
        $manager->persist($matiere);

        /* Salles */
        $salle = new Salle();
        $salle->setNumero("37.1.71")
            ->setCapacite(35);
        $manager->persist($salle);

        $manager->flush();
    }


}
