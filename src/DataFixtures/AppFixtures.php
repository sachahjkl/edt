<?php

namespace App\DataFixtures;

use App\Entity\Eleve;
use App\Entity\Formation;
use App\Entity\Groupe;
use App\Entity\Professeur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $prof = new Professeur();
        $prof->setPrenom("Cédric")
            ->setNom("Du Mouza")
            ->setUsername("cedricdumouza")
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$dWRReVFVZnUxd3Bwb1BBTw$cOEzjtZVqNXIM44836hSyDa4/YzU8SMdGDAdaFim+M8') //"password"
            ->setDateNaissance(new \DateTime("12 April 1976"));
        $manager->persist($prof);

        $formation = new Formation();
        $formation->setDureeAnnees(3)
            ->setIntitule("Ingénieur Système d'information")
            ->setResponsable($prof);

        $groupe = new Groupe();
        $groupe->setNom("fip1")
            ->setFormation($formation);

        $formation->addGroupe($groupe);

        $manager->persist($formation);
        $manager->persist($groupe);


        $eleve = new Eleve();
        $eleve->setUsername("sachafroment")
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$TXpxNEkvdTR5TGZZTUxKTQ$EdfX8q5y/fk5KWgsxATWMQSnGRkUfw863LKxbMOJa3Q') //"password"
            ->setPrenom("Sacha")
            ->setNom("Froment")
            ->setDateNaissance(new \DateTime("25 May 1999"))
            ->setGroupe($groupe);
        $manager->persist($eleve);

        $eleve = new Eleve();
        $eleve->setUsername("sivaajithansivathevan")
            ->setPassword('$argon2id$v=19$m=65536,t=4,p=1$TXpxNEkvdTR5TGZZTUxKTQ$EdfX8q5y/fk5KWgsxATWMQSnGRkUfw863LKxbMOJa3Q') //"password"
            ->setPrenom("Sivaajithan")
            ->setNom("Sivathevan")
            ->setDateNaissance(new \DateTime("8 June 1999"))
            ->setGroupe($groupe);

        $manager->persist($eleve);


        $manager->flush();
    }
}
