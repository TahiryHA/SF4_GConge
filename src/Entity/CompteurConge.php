<?php

namespace App\Entity;

use App\Repository\CompteurCongeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteurCongeRepository::class)
 */
class CompteurConge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=GestionConge::class, mappedBy="compteurConge_id")
     */
    private $gestionconges;

    /**
     * @ORM\ManyToOne(targetEntity=Type::class, inversedBy="compteurConges")
     */
    private $type_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acquis;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $restant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attente;

    public function __construct()
    {
        $this->gestionconges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|GestionConge[]
     */
    public function getGestionconges(): Collection
    {
        return $this->gestionconges;
    }

    public function addGestionconge(GestionConge $gestionconge): self
    {
        if (!$this->gestionconges->contains($gestionconge)) {
            $this->gestionconges[] = $gestionconge;
            $gestionconge->addCompteurCongeId($this);
        }

        return $this;
    }

    public function removeGestionconge(GestionConge $gestionconge): self
    {
        if ($this->gestionconges->contains($gestionconge)) {
            $this->gestionconges->removeElement($gestionconge);
            $gestionconge->removeCompteurCongeId($this);
        }

        return $this;
    }

    public function getTypeId(): ?Type
    {
        return $this->type_id;
    }

    public function setTypeId(?Type $type_id): self
    {
        $this->type_id = $type_id;

        return $this;
    }

    public function getAcquis(): ?string
    {
        return $this->acquis;
    }

    public function setAcquis(?string $acquis): self
    {
        $this->acquis = $acquis;

        return $this;
    }

    public function getRestant(): ?string
    {
        return $this->restant;
    }

    public function setRestant(?string $restant): self
    {
        $this->restant = $restant;

        return $this;
    }

    public function getAttente(): ?string
    {
        return $this->attente;
    }

    public function setAttente(?string $attente): self
    {
        $this->attente = $attente;

        return $this;
    }
}
