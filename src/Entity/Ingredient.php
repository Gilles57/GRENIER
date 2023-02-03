<?php

namespace App\Entity;

use App\Repository\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'integer')]
    private $Quantite;

    #[ORM\Column(type: 'string', length: 255)]
    private $UniteDeMesure;

    #[ORM\ManyToOne(targetEntity: Recette::class, inversedBy: 'Ingredients')]
    #[ORM\JoinColumn(nullable: false)]
    private $recette;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->Quantite;
    }

    public function setQuantite(int $Quantite): self
    {
        $this->Quantite = $Quantite;

        return $this;
    }

    public function getUniteDeMesure(): ?string
    {
        return $this->UniteDeMesure;
    }

    public function setUniteDeMesure(string $UniteDeMesure): self
    {
        $this->UniteDeMesure = $UniteDeMesure;

        return $this;
    }

    public function getRecette(): ?Recette
    {
        return $this->recette;
    }

    public function setRecette(?Recette $recette): self
    {
        $this->recette = $recette;

        return $this;
    }
}
