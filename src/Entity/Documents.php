<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentsRepository::class)
 */
class Documents
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
     * @ORM\ManyToOne(targetEntity=AccountType::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document_type;

    /**
     * @ORM\ManyToMany(targetEntity=Categories::class, inversedBy="documents")
     */
    private $categorie;

    /**
     * @ORM\ManyToMany(targetEntity=CompaniesType::class, inversedBy="documents")
     */
    private $company_type;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modified_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $legislation_modif;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $docModelName;

    /**
     * @ORM\OneToMany(targetEntity=UserDocuments::class, mappedBy="document")
     */
    private $userDocuments;


    public function __construct()
    {
        $this->categorie = new ArrayCollection();
        $this->company_type = new ArrayCollection();
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

    public function getDocumentType(): ?AccountType
    {
        return $this->document_type;
    }

    public function setDocumentType(?AccountType $document_type): self
    {
        $this->document_type = $document_type;

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categories $categorie): self
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie[] = $categorie;
        }

        return $this;
    }

    public function removeCategorie(Categories $categorie): self
    {
        if ($this->categorie->contains($categorie)) {
            $this->categorie->removeElement($categorie);
        }

        return $this;
    }

    /**
     * @return Collection|CompaniesType[]
     */
    public function getCompanyType(): Collection
    {
        return $this->company_type;
    }

    public function addCompanyType(CompaniesType $companyType): self
    {
        if (!$this->company_type->contains($companyType)) {
            $this->company_type[] = $companyType;
        }

        return $this;
    }

    public function removeCompanyType(CompaniesType $companyType): self
    {
        if ($this->company_type->contains($companyType)) {
            $this->company_type->removeElement($companyType);
        }

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeInterface
    {
        return $this->modified_at;
    }

    public function setModifiedAt(?\DateTimeInterface $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }

    public function getLegislationModif(): ?bool
    {
        return $this->legislation_modif;
    }

    public function setLegislationModif(bool $legislation_modif): self
    {
        $this->legislation_modif = $legislation_modif;

        return $this;
    }

    public function getDocModelName(): ?string
    {
        return $this->docModelName;
    }

    public function setDocModelName(string $docModelName): self
    {
        $this->docModelName = $docModelName;

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
            $userDocument->setDocument($this);
        }

        return $this;
    }

    public function removeUserDocument(UserDocuments $userDocument): self
    {
        if ($this->userDocuments->contains($userDocument)) {
            $this->userDocuments->removeElement($userDocument);
            // set the owning side to null (unless already changed)
            if ($userDocument->getDocument() === $this) {
                $userDocument->setDocument(null);
            }
        }

        return $this;
    }
}
