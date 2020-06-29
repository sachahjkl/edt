<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string")
 * @ORM\DiscriminatorMap({
 *     "exam" = "Examen",
 *     "cours" = "Cours"
 * })
 */
class Cours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * Assert\Type("DateTime")
     */
    private $debut;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="debut")
     * Assert\Type("DateTime")
     */
    private $fin;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $devoirs;

    /**
     * @ORM\Column(type="boolean")
     */
    private $statut = true;

    /**
     * @ORM\ManyToOne(targetEntity=Salle::class, inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $salle;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Professeur::class, inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $professeur;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="cours")
     * @ORM\JoinColumn(nullable=false)
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity=Absence::class, mappedBy="cours", orphanRemoval=true)
     */
    private $absences;

    public function __construct()
    {
        $this->absences = new ArrayCollection();
        $this->debut = new DateTime("now", new DateTimeZone("Europe/Paris"));
        $fin = clone $this->debut;
        date_add($fin, new DateInterval("PT1H30M"));
        $this->fin = $fin;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getDevoirs(): ?string
    {
        return $this->devoirs;
    }

    public function setDevoirs(?string $devoirs): self
    {
        $this->devoirs = $devoirs;

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

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->professeur;
    }

    public function setProfesseur(?Professeur $professeur): self
    {
        $this->professeur = $professeur;
        return $this;
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
            $absence->setCours($this);
        }

        return $this;
    }

    public function removeAbsence(Absence $absence): self
    {
        if ($this->absences->contains($absence)) {
            $this->absences->removeElement($absence);
            // set the owning side to null (unless already changed)
            if ($absence->getCours() === $this) {
                $absence->setCours(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return strval($this->getMatiere() . " - " . $this->getSalle());

    }

    /**
     * @Assert\Callback
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        $professeur = $this->getProfesseur();
        $matiere = $this->getMatiere();
        if ($professeur !== $matiere->getResponsable() && !$matiere->getProfesseurs()->contains($professeur)) {
            $context->buildViolation("Le professeur dispensant le cours doit être le responsable ou faire parti des professeurs autorisés.")
                ->atPath("professeur")
                ->addViolation();
        }
        if ($this->getDebut()->format("H") < 8) {
            $context->buildViolation("Les cours commencent à partir de 8h au plus tôt.")
                ->atPath("debut")
                ->addViolation();
        }
        if ($this->getFin()->format("H") > 20) {
            $context->buildViolation("Les cours se terminent à 20h au plus tard")
                ->atPath("fin")
                ->addViolation();
        }

        $debut = clone $this->debut;
        $fin_max = date_add($debut, new DateInterval("PT6H"));
        if ($this->getFin() > $fin_max) {
            $context->buildViolation("Un cours ne peut pas durer plus de 6H.")
                ->atPath("fin")
                ->addViolation();
        }

    }
}
