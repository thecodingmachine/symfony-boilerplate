<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource()]
class User implements UserInterface, \JsonSerializable, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column(length: 180)]
    private string $password;

    #[ORM\Column(length: 255)]
    private string|null $first_name = null;

    #[ORM\Column(length: 255)]
    private string|null $last_name = null;

    #[ORM\Column]
    private int|null $score = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Payment::class, orphanRemoval: true, fetch: 'EAGER')]
    private Collection $payments;

    #[ORM\Column]
    private \DateTimeImmutable|null $created_at = null;

    #[ORM\Column]
    private \DateTimeImmutable|null $modified_at = null;

    /** @param array<string> $roles */
    public function __construct(#[ORM\Column(length: 180, unique: true)]
    private string $email, #[ORM\Column]
    private array $roles = ['ROLE_USER'],)
    {
        $this->payments = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
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

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getlastName(),
            'score' => $this->getScore(),
            'payments_pending' => $this->getPaymentsWithoutPlace(),
            'roles' => $this->getRoles(),
        ];
    }

    public function getFirstName(): string|null
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): string|null
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getScore(): int|null
    {
        return $this->score;
    }

    public function setScore(int $score): static
    {
        $this->score = $score;

        return $this;
    }

    /** @return Collection<int, Payment> */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function getPaymentsWithoutPlace(): int
    {
        return $this->payments->filter(static function (Payment $payment) {
            return \is_null($payment->getPlace());
        })->count();
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setUser($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUser() === $this) {
                $payment->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): \DateTimeImmutable|null
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getModifiedAt(): \DateTimeImmutable|null
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeImmutable $modified_at): static
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    /**
     * Adds a specified score to the user's current score.
     *
     * @return $this
     */
    public function addScore(int $pointsToAdd): static
    {
        $this->score += $pointsToAdd;

        return $this;
    }
}
