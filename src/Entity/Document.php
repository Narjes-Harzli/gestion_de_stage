<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Demandestage;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $fichier = null;

    #[ORM\Column]
    private ?\DateTime $dateupload = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Demandestage $demandestage = null;

    #[ORM\Column(length: 50, options: ['default' => 'En attente'])]
    private string $statutValidation = 'En attente';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): static
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getDateupload(): ?\DateTime
    {
        return $this->dateupload;
    }

    public function setDateupload(\DateTime $dateupload): static
    {
        $this->dateupload = $dateupload;

        return $this;
    }

    public function getDemandestage(): ?Demandestage
    {
        return $this->demandestage;
    }

    public function setDemandestage(?Demandestage $demandestage): static
    {
        $this->demandestage = $demandestage;
        return $this;
    }

    public function getStatutValidation(): string
    {
        return $this->statutValidation;
    }

    public function setStatutValidation(string $statutValidation): static
    {
        $this->statutValidation = $statutValidation;
        return $this;
    }
}
