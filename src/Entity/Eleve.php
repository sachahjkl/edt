<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 */
class Eleve extends Personne
{

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity=Absence::class, mappedBy="absent", orphanRemoval=true)
     */
    private $absences;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="eleve", orphanRemoval=true)
     */
    private $notes;

    public function __construct()
    {
        parent::__construct();
        $this->absences = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_ELEVE';
        return array_unique($roles);
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection|Absence[]
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absence $absence): self
    {
        if (!$this->absences->contains($absence)) {
            $this->absences[] = $absence;
            $absence->setAbsent($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): self
    {
        if ($this->absences->contains($absence)) {
            $this->absences->removeElement($absence);
            // set the owning side to null (unless already changed)
            if ($absence->getAbsent() === $this) {
                $absence->setAbsent(null);
            }
        }

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
            $note->setEleve($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getEleve() === $this) {
                $note->setEleve(null);
            }
        }

        return $this;
    }

    public function getItems(): array
    {
        $items = parent::getItems();
        $items_child = [
        ];

        if (empty($items))
            return $items_child;
        else
            return array_merge($items, $items_child);
    }

}
