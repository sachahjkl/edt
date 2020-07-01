<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MatiereRepository::class)
 */
class Matiere
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $intitule;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\PositiveOrZero()
     * @Assert\LessThanOrEqual(200)
     */
    private $credits_ects;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="matiere", orphanRemoval=true)
     */
    private $cours;

    /**
     * @ORM\OneToOne(targetEntity=Professeur::class, inversedBy="matiere_responsable", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    /**
     * @ORM\ManyToMany(targetEntity=Professeur::class, inversedBy="matieres")
     */
    private $professeurs;

    public function __construct()
    {
        $this->enseigne = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->professeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getCreditsECTS(): ?int
    {
        return $this->credits_ects;
    }

    public function setCreditsECTS(int $creditsECTS): self
    {
        $this->credits_ects = $creditsECTS;

        return $this;
    }

    public function getResponsable(): ?Professeur
    {
        return $this->responsable;
    }

    public function setResponsable(Professeur $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * @return Collection|Professeur[]
     */
    public function getEnseigne(): Collection
    {
        return $this->enseigne;
    }

    public function addEnseigne(Professeur $enseigne): self
    {
        if (!$this->enseigne->contains($enseigne)) {
            $this->enseigne[] = $enseigne;
        }

        return $this;
    }

    public function removeEnseigne(Professeur $enseigne): self
    {
        if ($this->enseigne->contains($enseigne)) {
            $this->enseigne->removeElement($enseigne);
        }

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setMatiere($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->contains($cour)) {
            $this->cours->removeElement($cour);
            // set the owning side to null (unless already changed)
            if ($cour->getMatiere() === $this) {
                $cour->setMatiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Professeur[]
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) {
            $this->professeurs[] = $professeur;
        }

        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        if ($this->professeurs->contains($professeur)) {
            $this->professeurs->removeElement($professeur);
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->getIntitule());
    }
}
