<?php

namespace App\Entity;

use App\Repository\AdministrateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AdministrateurRepository::class)
 */
class Administrateur extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity=Actualite::class, mappedBy="auteur", orphanRemoval=true)
     */
    private $actualites;

    public function __construct()
    {
        $this->actualites = new ArrayCollection();
    }

    /**
     * @return Collection|Actualite[]
     */
    public function getActualites(): Collection
    {
        return $this->actualites;
    }

    public function addActualite(Actualite $actualite): self
    {
        if (!$this->actualites->contains($actualite)) {
            $this->actualites[] = $actualite;
            $actualite->setAuteur($this);
        }

        return $this;
    }

    public function removeActualite(Actualite $actualite): self
    {
        if ($this->actualites->contains($actualite)) {
            $this->actualites->removeElement($actualite);
            // set the owning side to null (unless already changed)
            if ($actualite->getAuteur() === $this) {
                $actualite->setAuteur(null);
            }
        }

        return $this;
    }
}
