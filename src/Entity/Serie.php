<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\SeriesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeriesRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Serie
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titre;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $titreOriginal;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $anneeSortie;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $media;

    #[ORM\Column(type: 'boolean')]
    private ?bool $vu;

    #[ORM\Column(type: 'boolean')]
    private ?bool $aGarder;

    #[ORM\Column(type: 'boolean')]
    private ?bool $coupDeCoeur;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getTitreOriginal(): ?string
    {
        return $this->titreOriginal;
    }

    public function setTitreOriginal(?string $titreOriginal): self
    {
        $this->titreOriginal = $titreOriginal;

        return $this;
    }

    public function getAnneeSortie(): ?int
    {
        return $this->anneeSortie;
    }

    public function setAnneeSortie(?int $anneeSortie): self
    {
        $this->anneeSortie = $anneeSortie;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getAGarder(): ?bool
    {
        return $this->aGarder;
    }

    public function setAGarder(bool $aGarder): self
    {
        $this->aGarder = $aGarder;

        return $this;
    }

    public function getCoupDeCoeur(): ?bool
    {
        return $this->coupDeCoeur;
    }

    public function setCoupDeCoeur(bool $coupDeCoeur): self
    {
        $this->coupDeCoeur = $coupDeCoeur;

        return $this;
    }
}
