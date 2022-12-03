<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use App\Services\AEScrypto;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: "Il existe déjà un compte associé à cet email")]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ", groups: ['standard'])]
    #[Assert\Length(
        min: 4,
        max: 16,
        minMessage: "Ce champ doit contenir au moins {{ limit }} caractères, {{ value }} n'est pas correct",
        maxMessage: "Ce champ ne peut pas contenir plus de {{ limit }} caractères",
        groups: ['standard']
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 45)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ", groups: ['standard'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ", groups: ['standard'])]
    #[Assert\Email( message: "{{ value }} n'est pas un email valide." , groups: ['standard'])] 
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ", groups: ['standard'])]
    private ?string $address = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ", groups: ['passwordreset'])]
    #[Assert\Length(
        min: 4,
        max: 20,
        minMessage: "Ce champ doit contenir au moins {{ limit }} caractères, {{ value }} n'est pas correct",
        maxMessage: "Ce champ ne peut pas contenir plus de {{ limit }} caractères",
        groups: ['passwordreset']
    )]
    private ?string $password = null;

    #[ORM\Column(length: 128)]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ", groups: ['passwordreset'])]
    #[Assert\Length(
        min: 4,
        max: 20,
        minMessage: "Ce champ doit contenir au moins {{ limit }} caractères, {{ value }} n'est pas correct",
        maxMessage: "Ce champ ne peut pas contenir plus de {{ limit }} caractères",
        groups: ['passwordreset']
    )]
    #[Assert\IdenticalTo(['propertyPath' => 'password',
                          'message' => "Les deux mots de passe doivent être identiques",
                          'groups' => ['passwordreset']
    ])]
    private ?string $confirmpassword = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[ORM\Column]
    private array $role = [];

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastlogin = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $confirmed = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    } 
    public function setEmail(string $email): self
    {
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getRole(): array
    {
        return $this->role;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getLastlogin(): ?\DateTimeInterface
    {
        return $this->lastlogin;
    }

    public function setLastlogin(?\DateTimeInterface $lastlogin): self
    {
        $this->lastlogin = $lastlogin;

        return $this;
    }

    public function getConfirmpassword(): ?string
    {
        return $this->confirmpassword;
    }

    public function setConfirmpassword(string $confirmpassword): self
    {
        $this->confirmpassword = $confirmpassword;

        return $this;
    }

        /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->role;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getConfirmed(): ?\DateTimeInterface
    {
        return $this->confirmed;
    }

    public function setConfirmed(?\DateTimeInterface $confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }
    // Handling email encryption / decryption
    // #[ORM\PrePersist]
    // public function onPrePersist() { 
    //     $AES = new AEScrypto($_ENV['AESKEY']);
    //     $this->email = $AES->encrypt($this->email);
    // }
    // #[ORM\PreUpdate]
    // public function onPreUpdate() {
    //     $AES = new AEScrypto($_ENV['AESKEY']);
    //     $this->email = $AES->encrypt($this->email);
    // }
    // #[ORM\PostLoad]
    // public function onPostLoad() {
    //     $AES = new AEScrypto($_ENV['AESKEY']);
    //     $this->email = $AES->decrypt($this->email);
    // }
}
