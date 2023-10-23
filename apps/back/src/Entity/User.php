<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, \JsonSerializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column(length: 180)]
    private string $password;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private Company|null $company = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private File|null $profilePicture = null;

    /** @param array<string> $roles */
    public function __construct(
        #[ORM\Column(length: 180, unique: true)]
        private string $email,
        #[ORM\Column]
        private array $roles = ['ROLE_USER'],
    ) {
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /** @deprecated since Symfony 5.3, use getUserIdentifier instead */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * This is "primary" role
     *
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /** @param array<string> $roles */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * DO NOT USE this method, it is required for the interface UserInterface
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * DO NOT USE this method, it is required for the interface UserInterface
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see UserInterface
     */
    public function getSalt(): string|null
    {
        return null;
    }

    /**
     * called after authentication
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /** @return array<string,int|string|null> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
        ];
    }

    public function getCompany(): Company|null
    {
        return $this->company;
    }

    public function setCompany(Company|null $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getProfilePicture(): File|null
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(File|null $profilePicture): void
    {
        $this->profilePicture = $profilePicture;
    }
}
