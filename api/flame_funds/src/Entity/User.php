<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NoSuspiciousCharacters]
    #[Assert\NotBlank]
    private ?string $username;
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex("/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/")]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Account::class, fetch: "EAGER")]
    private Collection $accounts;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\Column(nullable: true)]
    private ?int $currentAccount = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AccountHistory::class)]
    private Collection $accountHistories;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $sheetId = null;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->accountHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): static
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->setUser($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): static
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getUser() === $this) {
                $account->setUser(null);
            }
        }

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCurrentAccount(): ?int
    {
        return $this->currentAccount;
    }

    public function setCurrentAccount(?int $currentAccount): static
    {
        $this->currentAccount = $currentAccount;

        return $this;
    }

    /**
     * @return Collection<int, AccountHistory>
     */
    public function getAccountHistories(): Collection
    {
        return $this->accountHistories;
    }

    public function addAccountHistory(AccountHistory $accountHistory): static
    {
        if (!$this->accountHistories->contains($accountHistory)) {
            $this->accountHistories->add($accountHistory);
            $accountHistory->setUser($this);
        }

        return $this;
    }

    public function removeAccountHistory(AccountHistory $accountHistory): static
    {
        if ($this->accountHistories->removeElement($accountHistory)) {
            // set the owning side to null (unless already changed)
            if ($accountHistory->getUser() === $this) {
                $accountHistory->setUser(null);
            }
        }

        return $this;
    }

    public function getSheetId(): ?string
    {
        return $this->sheetId;
    }

    public function setSheetId(string $sheetId): static
    {
        $this->sheetId = $sheetId;

        return $this;
    }
}
