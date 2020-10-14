<?php

namespace App\Entity;

use App\Repository\CompaniesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompaniesRepository::class)
 */
class Companies
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $siren;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, mappedBy="companies")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=CompaniesType::class, inversedBy="companies")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=UserDocuments::class, mappedBy="company")
     */
    private $userDocuments;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->userDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSiren(): ?int
    {
        return $this->siren;
    }

    public function setSiren(int $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addCompany($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeCompany($this);
        }

        return $this;
    }

    public function getType(): ?CompaniesType
    {
        return $this->type;
    }

    public function setType(?CompaniesType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|UserDocuments[]
     */
    public function getUserDocuments(): Collection
    {
        return $this->userDocuments;
    }

    public function addUserDocument(UserDocuments $userDocument): self
    {
        if (!$this->userDocuments->contains($userDocument)) {
            $this->userDocuments[] = $userDocument;
            $userDocument->setCompany($this);
        }

        return $this;
    }

    public function removeUserDocument(UserDocuments $userDocument): self
    {
        if ($this->userDocuments->contains($userDocument)) {
            $this->userDocuments->removeElement($userDocument);
            // set the owning side to null (unless already changed)
            if ($userDocument->getCompany() === $this) {
                $userDocument->setCompany(null);
            }
        }

        return $this;
    }
}
