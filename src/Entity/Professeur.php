<?php

namespace App\Entity;

use App\Repository\ProfesseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProfesseurRepository::class)
 */
class Professeur extends Personne
{

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="professeur")
     */
    private $cours;

    /**
     * @ORM\OneToOne(targetEntity=Matiere::class, mappedBy="responsable", cascade={"persist", "remove"})
     */
    private $matiere_responsable;

    /**
     * @ORM\OneToOne(targetEntity=Formation::class, mappedBy="responsable", cascade={"persist", "remove"})
     */
    private $formation_responsable;

    /**
     * @ORM\ManyToMany(targetEntity=Matiere::class, mappedBy="professeurs")
     */
    private $matieres;

    public function __construct()
    {
        parent::__construct();
        $this->matieres = new ArrayCollection();
        $this->cours = new ArrayCollection();
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_PROF';
        return array_unique($roles);
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->addEnseigne($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->contains($matiere)) {
            $this->matieres->removeElement($matiere);
            $matiere->removeEnseigne($this);
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
            $cour->setProfesseur($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->contains($cour)) {
            $this->cours->removeElement($cour);
            // set the owning side to null (unless already changed)
            if ($cour->getProfesseur() === $this) {
                $cour->setProfesseur(null);
            }
        }

        return $this;
    }

    public function getMatiereResponsable(): ?Matiere
    {
        return $this->matiere_responsable;
    }

    public function setMatiereResponsable(Matiere $matiere_responsable): self
    {
        $this->matiere_responsable = $matiere_responsable;

        // set the owning side of the relation if necessary
        if ($matiere_responsable->getResponsable() !== $this) {
            $matiere_responsable->setResponsable($this);
        }

        return $this;
    }

    public function getFormationResponsable(): ?Formation
    {
        return $this->formation_responsable;
    }

    public function setFormationResponsable(Formation $formation_responsable): self
    {
        $this->formation_responsable = $formation_responsable;

        // set the owning side of the relation if necessary
        if ($formation_responsable->getResponsable() !== $this) {
            $formation_responsable->setResponsable($this);
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->getPrenom() . " " . $this->getNom());
    }

    public function getItems(): array
    {
        $items = parent::getItems();
        $items_child = [
            "Cours & examens" => "prof_cours",
        ];

        if (empty($items))
            return $items_child;
        else
            return array_merge($items, $items_child);
    }

}
