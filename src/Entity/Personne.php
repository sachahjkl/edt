<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string")
 * @ORM\DiscriminatorMap({
 *     "eleve" = "Eleve",
 *     "prof" = "Professeur"
 * })
 */
abstract class Personne extends Utilisateur
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @ORM\Column(type="date")
     * @Assert\Type("DateTime")
     */
    private $date_naissance;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="proprietaire", orphanRemoval=true)
     */
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setProprietaire($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getProprietaire() === $this) {
                $document->setProprietaire(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        $roles = parent::getRoles();
        $roles[] = 'ROLE_PERSON';
        return array_unique($roles);
    }

    public function __toString()
    {
        return strval($this->getPrenom() . " " . strtoupper($this->getNom()));
    }


    public function getDropdowns(): array
    {
        $dropdowns = parent::getDropdowns();
        $dropdowns_child = [
//            "Examens" => [
//                "logout" => "security_logout",
//                "not logout" => "security_logout"
//            ],
//            "Lil boy" => [
//                "logout" => "security_logout",
//                "not logout" => "security_logout"
//            ]
        ];
        if (empty($dropdowns))
            return $dropdowns_child;
        else
            return array_merge($dropdowns, $dropdowns_child);
    }

    public function getItems(): array
    {
        $items = parent::getItems();
        $items_child = [
            "Accueil" => "personne_index",
        ];

        if (empty($items))
            return $items_child;
        else
            return array_merge($items, $items_child);

    }

}
