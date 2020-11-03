<?php

namespace App\Entity;

use App\Repository\FacturesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FacturesRepository::class)
 */
class Factures
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
    private $clientName;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emitted;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $prix_ht;

    /**
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $prix_ttc;


    /**
     * @ORM\ManyToOne(targetEntity=FacturesCategories::class, inversedBy="factures")
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file_path;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    private $tva;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $factureName;

    private $coutTVA;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="factures")
     */
    private $user;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getEmitted(): ?bool
    {
        return $this->emitted;
    }

    public function setEmitted(bool $emitted): self
    {
        $this->emitted = $emitted;

        return $this;
    }


    public function getCategorie(): ?FacturesCategories
    {
        return $this->categorie;
    }

    public function setCategorie(?FacturesCategories $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->file_path;
    }

    public function setFilePath(string $file_path): self
    {
        $this->file_path = $file_path;

        return $this;
    }


    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFactureName(): ?string
    {
        return $this->factureName;
    }

    public function setFactureName(string $factureName): self
    {
        $this->factureName = $factureName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCoutTVA()
    {
        $this->coutTVA = $this->prix_ttc - $this->prix_ht;

        return $this->coutTVA;
    }

    /**
     * @param mixed $coutTVA
     */
    public function setCoutTVA($coutTVA): void
    {
        $this->coutTVA = $this->prix_ttc - $this->prix_ht;

        $this->coutTVA = $coutTVA;
    }

    /**
     * @return mixed
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param mixed $tva
     */
    public function setTva($tva): void
    {
        $this->tva = $tva;
    }

    /**
     * @return mixed
     */
    public function getPrixHt()
    {
        return $this->prix_ht;
    }

    /**
     * @param mixed $prix_ht
     */
    public function setPrixHt($prix_ht): void
    {
        $this->prix_ht = $prix_ht;
    }

    /**
     * @return mixed
     */
    public function getPrixTtc()
    {
        return $this->prix_ttc;
    }

    /**
     * @param mixed $prix_ttc
     */
    public function setPrixTtc($prix_ttc): void
    {
        $this->prix_ttc = $prix_ttc;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

}
