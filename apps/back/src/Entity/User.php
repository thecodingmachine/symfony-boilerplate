<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    /** @param array<string> $roles */
    public function __construct(
        #[ORM\Column(length: 180, unique: true)]
        private readonly string $email,
        #[ORM\Column]
        private array $roles,
    ) {
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getEmail(): string|null
    {
        return $this->email;
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

    /** @deprecated since Symfony 5.3, use getUserIdentifier instead */
    public function getUsername(): string
    {
        return (string) $this->email;
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

    /**
     * DO NOT USE this method, it is required for the interface UserInterface
     * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string|null
    {
        return null;
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

    public function jsonSerialize(): mixed
    {
        return [
            'email' => $this->getEmail(),
        ];
    }
}
