<?php

namespace App\Entity;

use App\Repository\DemandestageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandestageRepository::class)]
class Demandestage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\Column]
    private ?\DateTime $datedemande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDatedemande(): ?\DateTime
    {
        return $this->datedemande;
    }

    public function setDatedemande(\DateTime $datedemande): static
    {
        $this->datedemande = $datedemande;

        return $this;
    }
}
