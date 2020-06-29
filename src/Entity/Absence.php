<?php

namespace App\Entity;

use App\Repository\AbsenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=AbsenceRepository::class)
 * @UniqueEntity(
 *     fields={"absent", "cours"},
 *     errorPath="absent",
 *     message="Une absence a déjà attribuée à cet élève pour ce cours."
 * )
 */
class Absence
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $motif;

    /**
     * @ORM\Column(type="boolean")
     * Si l'absence est active ou non ( false = pas justifié, true = justifiée)
     */
    private $statut = false;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="absences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $absent;

    /**
     * @ORM\ManyToOne(targetEntity=Cours::class, inversedBy="absences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cours;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAbsent(): ?Eleve
    {
        return $this->absent;
    }

    public function setAbsent(?Eleve $absent): self
    {
        $this->absent = $absent;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }

    public function __toString()
    {
        return strval("Absence de " . $this->getAbsent() . " au cours de " . $this->getCours());
    }
}
