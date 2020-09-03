<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeRepository::class)
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=CompteurConge::class, mappedBy="type_id")
     */
    private $compteurConges;

    public function __construct()
    {
        $this->compteurConges = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|CompteurConge[]
     */
    public function getCompteurConges(): Collection
    {
        return $this->compteurConges;
    }

    public function addCompteurConge(CompteurConge $compteurConge): self
    {
        if (!$this->compteurConges->contains($compteurConge)) {
            $this->compteurConges[] = $compteurConge;
            $compteurConge->setTypeId($this);
        }

        return $this;
    }

    public function removeCompteurConge(CompteurConge $compteurConge): self
    {
        if ($this->compteurConges->contains($compteurConge)) {
            $this->compteurConges->removeElement($compteurConge);
            // set the owning side to null (unless already changed)
            if ($compteurConge->getTypeId() === $this) {
                $compteurConge->setTypeId(null);
            }
        }

        return $this;
    }
}
