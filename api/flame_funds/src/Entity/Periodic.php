<?php

namespace App\Entity;

use App\Repository\PeriodicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodicRepository::class)]
class Periodic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?string $days = null;

    #[ORM\Column(length: 512, nullable: true)]
    private ?string $details = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\ManyToOne(inversedBy: 'financialGoal')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;


    #[ORM\ManyToOne(inversedBy: 'expenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ExpenseCategory $category = null;

    #[ORM\OneToMany(mappedBy: 'periodic', targetEntity: PeriodicDetails::class)]
    private Collection $periodicDetails;

    public function __construct()
    {
        $this->periodicDetails = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(?\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function setDateEnd(?\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDays(?string $days): static
    {
        $this->days = $days;
        return $this;
    }

    public function getDays(): ?string
    {
        return $this->days;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;
        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(?bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;
        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;
        return $this;
    }

    public function getCategory(): ?ExpenseCategory
    {
        return $this->category;
    }

    public function setCategory(?ExpenseCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, PeriodicDetails>
     */
    public function getPeriodicDetails(): Collection
    {
        return $this->periodicDetails;
    }

    public function addPeriodicDetail(PeriodicDetails $periodicDetail): static
    {
        if (!$this->periodicDetails->contains($periodicDetail)) {
            $this->periodicDetails->add($periodicDetail);
            $periodicDetail->setPeriodic($this);
        }

        return $this;
    }

    public function removePeriodicDetail(PeriodicDetails $periodicDetail): static
    {
        if ($this->periodicDetails->removeElement($periodicDetail)) {
            // set the owning side to null (unless already changed)
            if ($periodicDetail->getPeriodic() === $this) {
                $periodicDetail->setPeriodic(null);
            }
        }

        return $this;
    }
}