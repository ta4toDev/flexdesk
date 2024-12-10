<?php

namespace App\Entity;

use App\Repository\BuchungRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuchungRepository::class)]
class Buchung
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datum = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $startzeit = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $endzeit = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column]
    private ?int $raum = null;

    #[ORM\Column]
    private ?int $tisch = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $benutzer = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatum(): ?\DateTimeInterface
    {
        return $this->datum;
    }

    public function setDatum(\DateTimeInterface $datum): static
    {
        $this->datum = $datum;

        return $this;
    }

    public function getStartzeit(): ?\DateTimeInterface
    {
        return $this->startzeit;
    }

    public function setStartzeit(\DateTimeInterface $startzeit): static
    {
        $this->startzeit = $startzeit;

        return $this;
    }

    public function getEndzeit(): ?\DateTimeInterface
    {
        return $this->endzeit;
    }

    public function setEndzeit(\DateTimeInterface $endzeit): static
    {
        $this->endzeit = $endzeit;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getRaum(): ?int
    {
        return $this->raum;
    }

    public function setRaum(int $raum): static
    {
        $this->raum = $raum;

        return $this;
    }

    public function getTisch(): ?int
    {
        return $this->tisch;
    }

    public function setTisch(int $tisch): static
    {
        $this->tisch = $tisch;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getBenutzer(): ?User
    {
        return $this->benutzer;
    }

    /**
     * @param User|null $benutzer
     */
    public function setBenutzer(?User $benutzer): static
    {
        $this->benutzer = $benutzer;

        return $this;
    }
}
