<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Film
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

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $media;

    #[ORM\Column(type: 'boolean')]
    private ?bool $vu;

    #[ORM\Column(type: 'boolean')]
    private ?bool $aGarder;

    #[ORM\Column(type: 'boolean')]
    private ?bool $coupDeCoeur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $code_tmbd = null;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'videos_films')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Version::class, inversedBy: 'videos_films')]
    private Collection $version;

    #[ORM\ManyToMany(targetEntity: Langue::class, inversedBy: 'videos_films')]
    private Collection $langues;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $extension = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $release_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\Column]
    private ?bool $aRemplacer = null;

    #[ORM\ManyToOne(inversedBy: 'films')]
    private ?Franchise $franchise = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slogan = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $resume = null;

    #[ORM\Column(length: 255)]
    private ?string $fichier_original = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_originale = null;


    public function __construct()
    {
        $this->genres = new ArrayCollection();
        $this->version = new ArrayCollection();
        $this->langues = new ArrayCollection();
    }

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

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getVu(): ?bool
    {
        return $this->vu;
    }

    public function setVu(bool $vu): self
    {
        $this->vu = $vu;

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



    public function getCodeTmbd(): ?string
    {
        return $this->code_tmbd;
    }

    public function setCodeTmbd(?string $code_tmbd): self
    {
        $this->code_tmbd = $code_tmbd;

        return $this;
    }

    public function getGenres()
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function addVersion(Version $version): self
    {
        if (!$this->version->contains($version)) {
            $this->version->add($version);
        }

        return $this;
    }

    public function removeVersion(Version $version): self
    {
        $this->version->removeElement($version);

        return $this;
    }

    public function getLangues()
    {
        return $this->langues;
    }

    public function addLangue(Langue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues->add($langue);
        }

        return $this;
    }

    public function removeLangue(Langue $langue): self
    {
        $this->langues->removeElement($langue);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(?string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeImmutable
    {
        return $this->release_date;
    }

    public function setReleaseDate(?\DateTimeImmutable $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function isARemplacer(): ?bool
    {
        return $this->aRemplacer;
    }

    public function setARemplacer(bool $aRemplacer): self
    {
        $this->aRemplacer = $aRemplacer;

        return $this;
    }

    public function getFranchise(): ?Franchise
    {
        return $this->franchise;
    }

    public function setFranchise(?Franchise $franchise): self
    {
        $this->franchise = $franchise;

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(?string $slogan): self
    {
        $this->slogan = $slogan;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): self
    {
        $this->resume = $resume;

        return $this;
    }

    public function getFichierOriginal(): ?string
    {
        return $this->fichier_original;
    }

    public function setFichierOriginal(string $fichier_original): self
    {
        $this->fichier_original = $fichier_original;

        return $this;
    }

    public function getDateOriginale(): ?string
    {
        return $this->date_originale;
    }

    public function setDateOriginale(?string $date_originale): self
    {
        $this->date_originale = $date_originale;

        return $this;
    }


}
