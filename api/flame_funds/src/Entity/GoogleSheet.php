<?php

namespace App\Entity;

use App\Repository\GoogleSheetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GoogleSheetRepository::class)]
class GoogleSheet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $spreadSheetId = null;

    #[ORM\Column(length: 255)]
    private ?string $sheetId = null;

    #[ORM\Column(length: 255)]
    private ?string $sheetName = null;

    #[ORM\ManyToOne(inversedBy: 'googleSheets')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpreadSheetId(): ?string
    {
        return $this->spreadSheetId;
    }

    public function setSpreadSheetId(string $spreadSheetId): static
    {
        $this->spreadSheetId = $spreadSheetId;

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

    public function getSheetName(): ?string
    {
        return $this->sheetName;
    }

    public function setSheetName(string $sheetName): static
    {
        $this->sheetName = $sheetName;

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
}
