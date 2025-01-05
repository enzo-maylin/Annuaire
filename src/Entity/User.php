<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\EntityListeners(['App\EventListener\UserListener'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_LOGIN', fields: ['login'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_CODE', fields: ['code'])]
#[UniqueEntity('login', message : "Ce login est déjà pris !")]
#[UniqueEntity('email', message : "Cet Email est déjà pris !")]
#[UniqueEntity('code', message : "Ce code est déjà pris !")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 32, minMessage: 'Le login doit faire au moins 4 caractères !', maxMessage: 'Le login doit faire moins de 32 caractères !')]
    private ?string $login = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Email(message: 'Pas au format Email')]
    #[Assert\Length(max: 255, maxMessage: 'L\'email doit faire moins de 255 caractères !')]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotNull]
    private ?bool $visibility = null;

    #[ORM\Column(length: 24, nullable: true)]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/',
        message: 'Le numéro de téléphone n\'est pas valide.'
    )]
    private ?string $mobile_number = null;

    #[ORM\Column(length: 24, nullable: true)]
    #[Assert\Regex(
        pattern: '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{0,2}){0,4}|\d{0,2}(?:[\s.-]?\d{0,3}){0,2})$/',
        message: 'Le numéro de téléphone n\'est pas valide.'
    )]
    private ?string $landline_number = null;

    #[ORM\Column(length: 32)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9]+$/',
        message: 'Le code n\'est pas valide.'
    )]
    #[Assert\Length(max: 32, maxMessage: 'Le code doit faire moins de 32 caractères !')]
    private ?string $code = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Le pays doit faire moins de 255 caractères !')]
    private ?string $country = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Le département doit faire moins de 255 caractères !')]
    private ?string $department = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Le métier doit faire moins de 255 caractères !')]
    private ?string $function = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'L\'adresse postal doit faire moins de 255 caractères !')]
    private ?string $postal_adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Le nom doit faire moins de 255 caractères !')]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: 'Le prénom doit faire moins de 255 caractères !')]
    private ?string $first_name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $last_signin = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function addRole($role) : void {

        if(!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getMobileNumber(): ?string
    {
        return $this->mobile_number;
    }

    public function setMobileNumber(?string $mobile_number): static
    {
        $this->mobile_number = $mobile_number;

        return $this;
    }

    public function getLandlineNumber(): ?string
    {
        return $this->landline_number;
    }

    public function setLandlineNumber(?string $landline_number): static
    {
        $this->landline_number = $landline_number;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getcountry(): ?string
    {
        return $this->country;
    }

    public function setcountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getfunction(): ?string
    {
        return $this->function;
    }

    public function setfunction(?string $function): static
    {
        $this->function = $function;

        return $this;
    }

    public function getPostalAdresse(): ?string
    {
        return $this->postal_adresse;
    }

    public function setPostalAdresse(?string $postal_adresse): static
    {
        $this->postal_adresse = $postal_adresse;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastSignin(): ?\DateTimeInterface
    {
        return $this->last_signin;
    }

    public function setLastSignin(?\DateTimeInterface $last_signin): static
    {
        $this->last_signin = $last_signin;

        return $this;
    }
}
