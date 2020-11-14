<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandeRepository::class)
 *
 */
class Commande
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emporter;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_retrait;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="float")
     */
    private $prix_total;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity=LigneCde::class, mappedBy="commande", orphanRemoval=true)
     */
    private $ligneCdes;

    public function __construct()
    {
        $this->ligneCdes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmporter(): ?bool
    {
        return $this->emporter;
    }

    public function setEmporter(bool $emporter): self
    {
        $this->emporter = $emporter;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->date_retrait;
    }

    public function setDateRetrait(\DateTimeInterface $date_retrait): self
    {
        $this->date_retrait = $date_retrait;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prix_total;
    }

    public function setPrixTotal(float $prix_total): self
    {
        $this->prix_total = $prix_total;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection|LigneCde[]
     */
    public function getLigneCdes(): Collection
    {
        return $this->ligneCdes;
    }

    public function addLigneCde(LigneCde $ligneCde): self
    {
        if (!$this->ligneCdes->contains($ligneCde)) {
            $this->ligneCdes[] = $ligneCde;
            $ligneCde->setCommande($this);
        }

        return $this;
    }

    public function removeLigneCde(LigneCde $ligneCde): self
    {
        if ($this->ligneCdes->contains($ligneCde)) {
            $this->ligneCdes->removeElement($ligneCde);
            // set the owning side to null (unless already changed)
            if ($ligneCde->getCommande() === $this) {
                $ligneCde->setCommande(null);
            }
        }

        return $this;
    }
}
