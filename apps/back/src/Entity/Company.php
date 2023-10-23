<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
class Company
{
    public const GROUP_LIST = 'companiesLst';
    public const GROUP_DETAILS = 'companiesDetails';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int|null $id = null;

    #[ORM\Column(length: 255)]
    private string $name;

    /** @var Collection<int, User> */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn]
    private File|null $indentityFile = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    #[Groups([self::GROUP_LIST, self::GROUP_DETAILS])]
    public function getId(): int|null
    {
        return $this->id;
    }

    #[Groups([self::GROUP_LIST, self::GROUP_DETAILS])]
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /** @return Collection<int, User> */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
    }

    public function getIndentityFile(): File|null
    {
        return $this->indentityFile;
    }

    public function setIndentityFile(File $indentityFile): static
    {
        $this->indentityFile = $indentityFile;

        return $this;
    }
}
