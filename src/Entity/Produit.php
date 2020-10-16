<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("categorieProduit:produits")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("categorieProduit:produits")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ("categorieProduit:produits")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     * @Groups ("categorieProduit:produits")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ("categorieProduit:produits")
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieProduit::class, inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorieProduit;

    /**
     * @ORM\Column(type="boolean")
     * @Groups ("categorieProduit:produits")
     */
    private $promo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCategorieProduit(): ?CategorieProduit
    {
        return $this->categorieProduit;
    }

    public function setCategorieProduit(?CategorieProduit $categorieProduit): self
    {
        $this->categorieProduit = $categorieProduit;

        return $this;
    }

    public function getPromo(): ?bool
    {
        return $this->promo;
    }

    public function setPromo(bool $promo): self
    {
        $this->promo = $promo;

        return $this;
    }
}
