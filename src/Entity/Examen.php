<?php

namespace App\Entity;

use App\Repository\ExamenRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ExamenRepository::class)
 */
class Examen extends Cours
{

    /**
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero
     */
    private $coefficient;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private $note_max;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="examen", orphanRemoval=true)
     */
    private $notes;

    public function __construct()
    {
        parent::__construct();
        $this->notes = new ArrayCollection();
    }

    public function getCoefficient(): ?float
    {
        return $this->coefficient;
    }

    public function setCoefficient(float $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }

    public function getNoteMax(): ?float
    {
        return $this->note_max;
    }

    public function setNoteMax(float $note_max): self
    {
        $this->note_max = $note_max;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setExamen($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getExamen() === $this) {
                $note->setExamen(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->getMatiere() . " - Note sur " . $this->getNoteMax() . " - Coefficient " . $this->getCoefficient());
    }
}
