<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column(length: 255)]
    private string|null $name = null;

    #[ORM\Column(length: 255)]
    private string|null $adress = null;

    #[ORM\Column(length: 255)]
    private string|null $gps_position = null;

    #[ORM\Column]
    private \DateTimeImmutable|null $created_at = null;

    #[ORM\Column]
    private \DateTimeImmutable|null $modified_at = null;

    #[ORM\OneToMany(mappedBy: 'place', targetEntity: Payment::class, orphanRemoval: true, fetch: 'EAGER')]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAdress(): string|null
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

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

    /** @return Collection<int, Payment> */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setPlace($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getPlace() === $this) {
                $payment->setPlace(null);
            }
        }

        return $this;
    }
}
