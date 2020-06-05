<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 * @Vich\Uploadable
 */
class Document
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file_path;

    /**
     * @Vich\UploadableField(mapping="personne_files", fileNameProperty="file_path")
     * @var File
     */
    private $file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $partage;

    /**
     * @ORM\ManyToOne(targetEntity=Personne::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath($file_path): self
    {
        $this->file_path = $file_path;

        return $this;
    }

    public function getPartage(): ?bool
    {
        return $this->partage;
    }

    public function setPartage(bool $partage): self
    {
        $this->partage = $partage;

        return $this;
    }

    public function getProprietaire(): ?Personne
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Personne $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function __toString()
    {
        return strval($this->getTitre());
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param File $file
     */
    public function setFile(?File $file = null): void
    {
        $this->file = $file;
        if ($file) {
            $this->file_path = $this->file_path . "";
        }
    }
}
