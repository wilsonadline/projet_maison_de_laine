<?php

namespace App\Entity;
use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity(repositoryClass=UsersRepository::class)
* @UniqueEntity(fields={"email"}, message="Vous avez déjà un compte")
*/
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string", length=180, unique=true)
    */
    private $email;

    /**
    * @ORM\Column(type="json")
    */
    private $roles = [];

    /**
    * @var string The hashed password
    * @ORM\Column(type="string")
    *  @Assert\Regex(
    * pattern = "#^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$#",
    * match=true,
    * message="Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole.")
    */
    private $password;

    /**
    * @ORM\Column(type="boolean")
    */
    private $isVerified = false;

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $name;

    /**
    * @ORM\Column(type="string", length=255)
    */
    private $firstname;

    /**
    * @ORM\Column(type="datetime", nullable=true)
    */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ad;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
    * A visual identifier that represents this user.
    *
    * @see UserInterface
    */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
    * @deprecated since Symfony 5.3, use getUserIdentifier instead
    */
    public function getUsername(): string
    {
        return (string) $this->email;
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
    * @see PasswordAuthenticatedUserInterface
    */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
    * Returning a salt is only needed, if you are not using a modern
    * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
    *
    * @see UserInterface
    */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
    * @see UserInterface
    */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(?int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAd(): ?string
    {
        return $this->ad;
    }

    public function setAd(?string $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getCp(): ?int
    {
        return $this->cp;
    }

    public function setCp(?int $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }
}