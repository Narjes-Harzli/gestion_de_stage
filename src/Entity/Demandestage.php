<?php

namespace App\Entity;

use App\Repository\DemandestageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use App\Entity\User;

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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $avisEncadrant = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDecision = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $valideFinStage = false;

    #[ORM\Column(nullable: true)]
    private ?int $note = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $appreciation = null;

    /* ========== VICH UPLOAD ========== */

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichier = null;                 // nom stocké en BD

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;      // date de modif

    #[Vich\UploadableField(mapping: 'demandes', fileNameProperty: 'nomFichier')]
    private ?File $fichierFile = null;                  // champ non persistant

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'demandestages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stage $stage = null;

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

    public function getAvisEncadrant(): ?string
    {
        return $this->avisEncadrant;
    }

    public function setAvisEncadrant(?string $avisEncadrant): static
    {
        $this->avisEncadrant = $avisEncadrant;
        return $this;
    }

    public function getDateDecision(): ?\DateTimeImmutable
    {
        return $this->dateDecision;
    }

    public function setDateDecision(?\DateTimeImmutable $dateDecision): static
    {
        $this->dateDecision = $dateDecision;
        return $this;
    }

    public function isValideFinStage(): bool
    {
        return $this->valideFinStage;
    }

    public function setValideFinStage(bool $valideFinStage): static
    {
        $this->valideFinStage = $valideFinStage;
        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): static
    {
        $this->note = $note;
        return $this;
    }

    public function getAppreciation(): ?string
    {
        return $this->appreciation;
    }

    public function setAppreciation(?string $appreciation): static
    {
        $this->appreciation = $appreciation;
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

    public function getEtudiant(): ?User
    {
        return $this->etudiant;
    }

    public function setEtudiant(?User $etudiant): static
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getStage(): ?Stage
    {
        return $this->stage;
    }

    public function setStage(?Stage $stage): static
    {
        $this->stage = $stage;

        return $this;
    }
}
