<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\User;

#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $typestage = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\ManyToOne(inversedBy: 'stages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Department $department = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $encadrant = null;

    #[ORM\OneToMany(targetEntity: Demandestage::class, mappedBy: 'stage')]
    private Collection $demandestages;

    public function __construct()
    {
        $this->demandestages = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTypestage(): ?string
    {
        return $this->typestage;
    }

    public function setTypestage(string $typestage): static
    {
        $this->typestage = $typestage;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getEncadrant(): ?User
    {
        return $this->encadrant;
    }

    public function setEncadrant(?User $encadrant): static
    {
        $this->encadrant = $encadrant;
        return $this;
    }

    /**
     * @return Collection<int, Demandestage>
     */
    public function getDemandestages(): Collection
    {
        return $this->demandestages;
    }

    public function addDemandestage(Demandestage $demandestage): static
    {
        if (!$this->demandestages->contains($demandestage)) {
            $this->demandestages->add($demandestage);
            $demandestage->setStage($this);
        }

        return $this;
    }

    public function removeDemandestage(Demandestage $demandestage): static
    {
        if ($this->demandestages->removeElement($demandestage)) {
            // set the owning side to null (unless already changed)
            if ($demandestage->getStage() === $this) {
                $demandestage->setStage(null);
            }
        }

        return $this;
    }

}
