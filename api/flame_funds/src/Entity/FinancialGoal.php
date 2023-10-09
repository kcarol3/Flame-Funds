<?php

namespace App\Entity;

use App\Repository\FinancialGoalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinancialGoalRepository::class)]
class FinancialGoal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $goalAmount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $currentAmount = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\OneToMany(mappedBy: 'goal', targetEntity: Account::class)]
    private Collection $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): void
    {
        $this->dateStart = $dateStart;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function getGoalAmount(): ?string
    {
        return $this->goalAmount;
    }

    public function setGoalAmount(?string $goalAmount): void
    {
        $this->goalAmount = $goalAmount;
    }

    public function getCurrentAmount(): ?string
    {
        return $this->currentAmount;
    }

    public function setCurrentAmount(?string $currentAmount): void
    {
        $this->currentAmount = $currentAmount;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): void
    {
        $this->dateEnd = $dateEnd;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): void
    {
        $this->details = $details;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): void
    {
        $this->isDeleted = $isDeleted;
    }


}