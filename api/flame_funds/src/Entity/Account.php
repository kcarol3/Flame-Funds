<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $balance = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $CreatedDate = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\ManyToOne(inversedBy: 'accounts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Expense::class)]
    private Collection $expenses;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Income::class)]
    private Collection $incomes;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: AccountHistory::class)]
    private Collection $accountHistories;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: FinancialGoal::class)]
    private Collection $financialGoal;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
        $this->incomes = new ArrayCollection();
        $this->accountHistories = new ArrayCollection();
        $this->financialGoal = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBalance(): ?string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->CreatedDate;
    }

    public function setCreatedDate(\DateTimeInterface $CreatedDate): static
    {
        $this->CreatedDate = $CreatedDate;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Expense>
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): static
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses->add($expense);
            $expense->setAccount($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): static
    {
        if ($this->expenses->removeElement($expense)) {
            // set the owning side to null (unless already changed)
            if ($expense->getAccount() === $this) {
                $expense->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FinancialGoal>
     */
    public function getFinancialGoal(): Collection
    {
        return $this->financialGoal;
    }

    public function addFinancialGoal(FinancialGoal $financialGoal): static
    {
        if (!$this->financialGoal->contains($financialGoal)) {
            $this->financialGoal->add($financialGoal);
            $financialGoal->setAccount($this);
        }

        return $this;
    }

    public function removeFinancialGoal(FinancialGoal $financialGoal): static
    {
        if ($this->financialGoal->removeElement($financialGoal)) {
            // set the owning side to null (unless already changed)
            if ($financialGoal->getAccount() === $this) {
                $financialGoal->setAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Income>
     */
    public function getIncomes(): Collection
    {
        return $this->incomes;
    }

    public function addIncome(Income $income): static
    {
        if (!$this->incomes->contains($income)) {
            $this->incomes->add($income);
            $income->setAccount($this);
        }

        return $this;
    }

    public function removeIncome(Income $income): static
    {
        if ($this->incomes->removeElement($income)) {
            // set the owning side to null (unless already changed)
            if ($income->getAccount() === $this) {
                $income->setAccount(null);
            }
        }

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
            $accountHistory->setAccount($this);
        }

        return $this;
    }

    public function removeAccountHistory(AccountHistory $accountHistory): static
    {
        if ($this->accountHistories->removeElement($accountHistory)) {
            // set the owning side to null (unless already changed)
            if ($accountHistory->getAccount() === $this) {
                $accountHistory->setAccount(null);
            }
        }

        return $this;
    }
}
