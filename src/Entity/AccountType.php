<?php

namespace App\Entity;

use App\Repository\AccountTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountTypeRepository::class)
 *
 */
class AccountType
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;
    

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="document_type")
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="acc_type")
     */
    private $users;



    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getTest(): Collection
    {
        return $this->test;
    }

    public function addTest(Users $test): self
    {
        if (!$this->test->contains($test)) {
            $this->test[] = $test;
            $test->setAccountType($this);
        }

        return $this;
    }

    public function removeTest(Users $test): self
    {
        if ($this->test->contains($test)) {
            $this->test->removeElement($test);
            // set the owning side to null (unless already changed)
            if ($test->getAccountType() === $this) {
                $test->setAccountType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Documents[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setDocumentType($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->contains($document)) {
            $this->documents->removeElement($document);
            // set the owning side to null (unless already changed)
            if ($document->getDocumentType() === $this) {
                $document->setDocumentType(null);
            }
        }

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
            $user->setAccountType($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getAccountType() === $this) {
                $user->setAccountType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getUsersl(): Collection
    {
        return $this->usersl;
    }

    public function addUsersl(Users $usersl): self
    {
        if (!$this->usersl->contains($usersl)) {
            $this->usersl[] = $usersl;
            $usersl->setAccountType($this);
        }

        return $this;
    }

    public function removeUsersl(Users $usersl): self
    {
        if ($this->usersl->contains($usersl)) {
            $this->usersl->removeElement($usersl);
            // set the owning side to null (unless already changed)
            if ($usersl->getAccountType() === $this) {
                $usersl->setAccountType(null);
            }
        }

        return $this;
    }
}
