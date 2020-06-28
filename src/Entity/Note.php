<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=NoteRepository::class)
 * @UniqueEntity(
 *     fields={"eleve", "examen"},
 *     errorPath="eleve",
 *     message="Une note a déjà attribuée à cet élève pour cet examen."
 * )
 */
class Note
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero
     * @Assert\LessThanOrEqual(propertyPath="examen.note_max")
     */
    private $note;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=Examen::class, inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $examen;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getExamen(): ?Examen
    {
        return $this->examen;
    }

    public function setExamen(?Examen $examen): self
    {
        $this->examen = $examen;

        return $this;
    }

    public function __toString()
    {
        return strval($this->getId());
    }
}
