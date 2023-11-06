<?php

namespace App\Entity;

use App\Repository\PeriodicDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodicDetailsRepository::class)]
class PeriodicDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 8, scale: 2)]
    private ?string $amount = null;

    #[ORM\ManyToOne(inversedBy: 'periodicDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Periodic $periodic = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPeriodic(): ?Periodic
    {
        return $this->periodic;
    }

    public function setPeriodic(?Periodic $periodic): static
    {
        $this->periodic = $periodic;

        return $this;
    }
}
