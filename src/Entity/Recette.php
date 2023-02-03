<?php

namespace App\Entity;

use App\Entity\Traits\Timestampable;
use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Recette
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titre;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $illustration;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $dureePreparation;

    #[ORM\OneToMany(mappedBy: 'secteur_recette', targetEntity: Ingredient::class, orphanRemoval: true)]
    private  $Ingredients;

    #[ORM\ManyToOne(targetEntity: CatRecette::class, inversedBy: 'recettes')]
    private ?CatRecette $categorie;

    public function __construct()
    {
        $this->Ingredients = new ArrayCollection();
        $this->Etapes = new ArrayCollection();
        $this->Categorie = new ArrayCollection();
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

    public function getIllustration(): ?string
    {
        return $this->illustration;
    }

    public function setIllustration(?string $illustration): self
    {
        $this->illustration = $illustration;

        return $this;
    }

    public function getDureePreparation(): ?string
    {
        return $this->dureePreparation;
    }

    public function setdureePreparation(string $dureePreparation): self
    {
        $this->dureePreparation = $dureePreparation;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->Ingredients;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->Ingredients->contains($ingredient)) {
            $this->Ingredients[] = $ingredient;
            $ingredient->setRecette($this);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->Ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getRecette() === $this) {
                $ingredient->setRecette(null);
            }
        }

        return $this;
    }



    public function getCategorie(): ?CatRecette
    {
        return $this->categorie;
    }

    public function setCategorie(?CatRecette $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
