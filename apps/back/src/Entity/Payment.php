<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private User|null $user = null;

    #[ORM\Column(length: 255)]
    private string|null $label = null;

    #[ORM\Column]
    private float|null $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private string|null $location = null;

    #[ORM\Column(length: 255)]
    private string|null $gps_position = null;

    #[ORM\ManyToOne(inversedBy: 'payments', targetEntity: Place::class, fetch: 'EAGER')]
    private Place|null $place = null;


    #[ORM\Column]
    private \DateTimeImmutable|null $created_at = null;

    #[ORM\Column]
    private \DateTimeImmutable|null $modified_at = null;

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getUser(): User|null
    {
        return $this->user;
    }

    public function setUser(User|null $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLabel(): string|null
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getAmount(): float|null
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getLocation(): string|null
    {
        if ($this->getPlace()) {
            $place_name = $this->getPlace()->getName();
            $place_adresse = $this->getPlace()->getAdress();

            return $place_name . ' - ' . $place_adresse;
        }

        return null;
    }

    public function setLocation(string|null $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getGpsPosition(): string|null
    {
        return $this->gps_position;
    }

    public function setGpsPosition(string $gps_position): static
    {
        $this->gps_position = $gps_position;

        return $this;
    }

    public function getPlace(): Place|null
    {
        return $this->place;
    }

    public function setPlace(Place|null $place): static
    {
        $this->place = $place;

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
}
