<?php

namespace App\Entity;

use App\Repository\WorkerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=WorkerRepository::class)
 */
class Worker
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
     * @ORM\ManyToOne(targetEntity=Societe::class, inversedBy="workers")
     */
    private $societe;

    /**
     * @ORM\ManyToMany(targetEntity=Conge::class, inversedBy="workers")
     */
    private $conge;

    /**
     * @ORM\OneToMany(targetEntity=GestionConge::class, mappedBy="worker_id")
     */
    private $gestionConges;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    public function __construct()
    {
        $this->conge = new ArrayCollection();
        $this->gestionConges = new ArrayCollection();
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

    public function getSociete(): ?Societe
    {
        return $this->societe;
    }

    public function setSociete(?Societe $societe): self
    {
        $this->societe = $societe;

        return $this;
    }

    /**
     * @return Collection|Conge[]
     */
    public function getConge(): Collection
    {
        return $this->conge;
    }

    public function addConge(Conge $conge): self
    {
        if (!$this->conge->contains($conge)) {
            $this->conge[] = $conge;
        }

        return $this;
    }

    public function removeConge(Conge $conge): self
    {
        if ($this->conge->contains($conge)) {
            $this->conge->removeElement($conge);
        }

        return $this;
    }

    /**
     * @return Collection|GestionConge[]
     */
    public function getGestionConges(): Collection
    {
        return $this->gestionConges;
    }

    public function addGestionConge(GestionConge $gestionConge): self
    {
        if (!$this->gestionConges->contains($gestionConge)) {
            $this->gestionConges[] = $gestionConge;
            $gestionConge->setWorkerId($this);
        }

        return $this;
    }

    public function removeGestionConge(GestionConge $gestionConge): self
    {
        if ($this->gestionConges->contains($gestionConge)) {
            $this->gestionConges->removeElement($gestionConge);
            // set the owning side to null (unless already changed)
            if ($gestionConge->getWorkerId() === $this) {
                $gestionConge->setWorkerId(null);
            }
        }

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }
}
