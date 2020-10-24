<?php

namespace App\Entity;

use App\Repository\AccountTypeRepository;
use App\Repository\UsersRepository;
use ContainerENym6bk\getAccountTypeRepositoryService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 */
class Users implements UserInterface
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
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $connected_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $verified_mail = 0;
    

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Articles::class, mappedBy="author")
     */
    private $articles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_writer = 0;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity=Companies::class, inversedBy="users")
     */
    private $companies;

    /**
     * @ORM\ManyToOne(targetEntity=AccountType::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acc_type;

    /**
     * @ORM\Column(type="boolean")
     */
    private $accountVerified = 0;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $account_ID_path;

    /**
     * @ORM\OneToMany(targetEntity=UserDocuments::class, mappedBy="user", cascade={"all"}, fetch="EAGER")
     */
    private $userDocuments;


    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->created_at = new \DateTime();
        $this->companies = new ArrayCollection();
        $this->userDocuments = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->login;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

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


    public function getConnectedAt(): ?\DateTimeInterface
    {
        return $this->connected_at;
    }

    public function setConnectedAt(?\DateTimeInterface $connected_at): self
    {
        $this->connected_at = $connected_at;

        return $this;
    }

    public function getVerifiedMail(): ?bool
    {
        return $this->verified_mail;
    }

    public function setVerifiedMail(bool $verified_mail): self
    {
        $this->verified_mail = $verified_mail;

        return $this;
    }

    public function getAccountVerified(): ?bool
    {
        return $this->accountVerified;
    }

    public function setAccountVerified(bool $account_verified): self
    {
        $this->accountVerified = $account_verified;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method
        $roles = $this->roles;

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->email;
    }

    public function getUserLogin()
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getIsWriter(): ?bool
    {
        return $this->is_writer;
    }

    public function setIsWriter(bool $is_writer): self
    {
        $this->is_writer = $is_writer;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|Companies[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Companies $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
        }

        return $this;
    }

    public function removeCompany(Companies $company): self
    {
        if ($this->companies->contains($company)) {
            $this->companies->removeElement($company);
        }

        return $this;
    }

    public function getAccType(): ?AccountType
    {
        return $this->acc_type;
    }

    public function setAccType(?AccountType $acc_type): self
    {
        $this->acc_type = $acc_type;

        return $this;
    }

    public function getAccountType(): ?AccountType
    {
        return $this->acc_type;
    }

    public function setAccountType(?AccountType $acc_type): self
    {
        $this->acc_type = $acc_type;

        return $this;
    }

    public function getAccountIDPath(): ?string
    {
        return $this->account_ID_path;
    }

    public function setAccountIDPath(?string $account_ID_path): self
    {
        $this->account_ID_path = $account_ID_path;

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
            $userDocument->setUser($this);
        }

        return $this;
    }

    public function removeUserDocument(UserDocuments $userDocument): self
    {
        if ($this->userDocuments->contains($userDocument)) {
            $this->userDocuments->removeElement($userDocument);
            // set the owning side to null (unless already changed)
            if ($userDocument->getUser() === $this) {
                $userDocument->setUser(null);
            }
        }

        return $this;
    }
}
