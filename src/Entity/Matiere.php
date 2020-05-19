<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $creditsECTS;

    /**
     * @ORM\OneToOne(targetEntity=Professeur::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $responsable;

    /**
     * @ORM\ManyToMany(targetEntity=Professeur::class, inversedBy="matieres")
     */
    private $enseigne;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="matiere", orphanRemoval=true)
     */
    private $cours;

    public function __construct()
    {
        $this->enseigne = new ArrayCollection();
        $this->cours = new ArrayCollection();
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
        return $this->creditsECTS;
    }

    public function setCreditsECTS(int $creditsECTS): self
    {
        $this->creditsECTS = $creditsECTS;

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
}
