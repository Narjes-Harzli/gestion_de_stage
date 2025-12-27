<?php

namespace App\Entity;

use App\Repository\DemandestageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DemandestageRepository::class)]
#[Vich\Uploadable]                                     // ← 1 activation Vich
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

    /* ========== VICH UPLOAD ========== */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichier = null;                 // nom stocké en BD

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;      // date de modif

    #[Vich\UploadableField(mapping: 'demandes', fileNameProperty: 'nomFichier')]
    private ?File $fichierFile = null;                  // champ non persistant

    /* ========== GETTERS / SETTERS ========== */

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

    /* ---------- upload ---------- */

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getFichierFile(): ?File
    {
        return $this->fichierFile;
    }

    public function setFichierFile(?File $fichierFile = null): static
    {
        $this->fichierFile = $fichierFile;
        if (null !== $fichierFile) {
            // force Doctrine à voir une modif
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }
}
