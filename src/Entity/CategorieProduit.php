<?php

namespace App\Entity;

use App\Repository\CategorieProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CategorieProduitRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"categorieProduit:produits"}},
 *     itemOperations={
 *         "get"
 *     },
 *     collectionOperations={
 *         "get"
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"nom": "exact"})
 */
class CategorieProduit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ("categorieProduit:produits")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ("categorieProduit:produits")
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Produit::class, mappedBy="categorieProduit")
     * @Groups ("categorieProduit:produits")
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

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

    /**
     * @return Collection|Produit[]
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits[] = $produit;
            $produit->setCategorieProduit($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
            // set the owning side to null (unless already changed)
            if ($produit->getCategorieProduit() === $this) {
                $produit->setCategorieProduit(null);
            }
        }

        return $this;
    }
}
