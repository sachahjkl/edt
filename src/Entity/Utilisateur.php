<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type",type="string")
 * @ORM\DiscriminatorMap({
 *     "personne" = "Personne",
 *     "admin" = "Administrateur",
 *     "eleve" = "Eleve",
 *     "prof" = "Professeur"
 * })
 * @Vich\Uploadable
 */
abstract class Utilisateur implements UserInterface, Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(min="6")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="6")
     */
    private $password;

    /**
     * @var string the plain password (local)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profile_picture_path;

    /**
     * @Vich\UploadableField(mapping="personne_profile_pictures", fileNameProperty="profile_picture_path")
     * @var File
     */
    private $profile_picture_file;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return strval($this->username);
    }

    /**
     * @return string
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     * @return Utilisateur
     */
    public function setPlainPassword(string $plainPassword): Utilisateur
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return File
     */
    public function getProfilePictureFile()
    {
        return $this->profile_picture_file;
    }

    /**
     * @param File $profile_picture_file
     * @return $this
     */
    public function setProfilePictureFile(?File $profile_picture_file = null): self
    {
        $this->profile_picture_file = $profile_picture_file;
        if (null !== $profile_picture_file) {
            $this->updatedAt = new DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfilePicturePath(): ?string
    {
        return $this->profile_picture_path;
    }

    /**
     * @param $profile_picture
     * @return $this
     */
    public function setProfilePicturePath($profile_picture): self
    {
        $this->profile_picture_path = $profile_picture;

        return $this;
    }

    public function getAvatarUri(): ?string
    {
        if ($this->getProfilePicturePath()) {
            return "/uploads/images/profile_pictures/" . $this->getProfilePicturePath();
        } else {
            return null;
        }
    }

    public function serialize()
    {
        $this->profile_picture_file = base64_encode($this->profile_picture_file);
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->profile_picture_path,
            $this->profile_picture_file
        ));
    }

    public function unserialize($serialized)
    {
        $this->profile_picture_file = base64_decode($this->profile_picture_file);
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->profile_picture_path,
            $this->profile_picture_file
            ) = unserialize($serialized);
    }

    public function getDropdowns(): array
    {
        return [];
    }

    public function getItems(): array
    {
        return [];
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
