<?php

namespace App\Entity;

use App\Repository\DemandeDeStageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeDeStageRepository::class)]
class DemandeDeStage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cvFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lettreFile = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCvFile(): ?string
    {
        return $this->cvFile;
    }

    public function setCvFile(?string $cvFile): static
    {
        $this->cvFile = $cvFile;

        return $this;
    }

    public function getLettreFile(): ?string
    {
        return $this->lettreFile;
    }

    public function setLettreFile(?string $lettreFile): static
    {
        $this->lettreFile = $lettreFile;

        return $this;
    }
}
