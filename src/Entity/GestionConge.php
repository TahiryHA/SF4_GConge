<?php

namespace App\Entity;

use App\Repository\GestionCongeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GestionCongeRepository::class)
 */
class GestionConge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $valeur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $de;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateInclus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity=CompteurConge::class, inversedBy="gestionconges")
     */
    private $compteurConge_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="gestionConges")
     */
    private $user;

    public function __construct()
    {
        $this->compteurConge_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?int
    {
        return $this->valeur;
    }

    public function setValeur(int $valeur): self
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDe(): ?\DateTimeInterface
    {
        return $this->de;
    }

    public function setDe(\DateTimeInterface $de): self
    {
        $this->de = $de;

        return $this;
    }

    public function getDateInclus(): ?\DateTimeInterface
    {
        return $this->dateInclus;
    }

    public function setDateInclus(\DateTimeInterface $dateInclus): self
    {
        $this->dateInclus = $dateInclus;

        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(string $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @return Collection|CompteurConge[]
     */
    public function getCompteurCongeId(): Collection
    {
        return $this->compteurConge_id;
    }

    public function addCompteurCongeId(CompteurConge $compteurCongeId): self
    {
        if (!$this->compteurConge_id->contains($compteurCongeId)) {
            $this->compteurConge_id[] = $compteurCongeId;
        }

        return $this;
    }

    public function removeCompteurCongeId(CompteurConge $compteurCongeId): self
    {
        if ($this->compteurConge_id->contains($compteurCongeId)) {
            $this->compteurConge_id->removeElement($compteurCongeId);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
