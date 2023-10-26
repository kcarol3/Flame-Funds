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

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ExpenseCategory::class)]
    private Collection $expenseCategory;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: IncomeCategory::class)]
    private Collection $incomeCategory;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->accountHistories = new ArrayCollection();
        $this->expenseCategory = new ArrayCollection();
        $this->incomeCategory = new ArrayCollection();
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

    /**
     * @return Collection<int, ExpenseCategory>
     */
    public function getExpenseCategory(): Collection
    {
        return $this->expenseCategory;
    }

    public function addExpenseCategory(ExpenseCategory $expenseCategory): static
    {
        if (!$this->expenseCategory->contains($expenseCategory)) {
            $this->expenseCategory->add($expenseCategory);
            $expenseCategory->setUser($this);
        }

        return $this;
    }

    public function removeExpenseCategory(ExpenseCategory $expenseCategory): static
    {
        if ($this->expenseCategory->removeElement($expenseCategory)) {
            // set the owning side to null (unless already changed)
            if ($expenseCategory->getUser() === $this) {
                $expenseCategory->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, IncomeCategory>
     */
    public function getIncomeCategory(): Collection
    {
        return $this->incomeCategory;
    }

    public function addIncomeCategory(IncomeCategory $incomeCategory): static
    {
        if (!$this->incomeCategory->contains($incomeCategory)) {
            $this->incomeCategory->add($incomeCategory);
            $incomeCategory->setUser($this);
        }

        return $this;
    }

    public function removeIncomeCategory(IncomeCategory $incomeCategory): static
    {
        if ($this->incomeCategory->removeElement($incomeCategory)) {
            // set the owning side to null (unless already changed)
            if ($incomeCategory->getUser() === $this) {
                $incomeCategory->setUser(null);
            }
        }

        return $this;
    }
}
