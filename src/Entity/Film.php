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

    #[ORM\ManyToMany(targetEntity: CatFilm::class, inversedBy: 'videos_films')]
    private  $category;

    #[ORM\Column(nullable: true)]
    private ?int $code_tmbd = null;

    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'videos_films')]
    private Collection $genres;

    #[ORM\ManyToMany(targetEntity: Version::class, inversedBy: 'videos_films')]
    private Collection $version;

    #[ORM\ManyToMany(targetEntity: Langue::class, inversedBy: 'videos_films')]
    private Collection $langues;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $extension = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $release_date = null;

    public function __construct()
    {
        $this->category = new ArrayCollection();
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

    /**
     * @return Collection<int, CatFilm>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(CatFilm $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(CatFilm $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function getCodeTmbd(): ?int
    {
        return $this->code_tmbd;
    }

    public function setCodeTmbd(?int $code_tmbd): self
    {
        $this->code_tmbd = $code_tmbd;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
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

    /**
     * @return Collection<int, Version>
     */
    public function getVersion(): Collection
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

    /**
     * @return Collection<int, Langue>
     */
    public function getLangues(): Collection
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
}
