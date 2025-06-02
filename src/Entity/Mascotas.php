<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MascotasRepository;
use App\Controller\ResetPasswordFormType;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: MascotasRepository::class)]
#[Broadcast]
class Mascotas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $edad = null;

    #[ORM\Column(length: 255)]
    private ?string $raza = null;

    #[ORM\Column(length: 255)]
    private ?string $animal = null;

    #[ORM\Column(length: 255)]
    private ?string $genero = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $ficha = null;

    #[ORM\ManyToOne(inversedBy: 'mascotas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cliente $cliente_id = null;

    /**
     * @var Collection<int, Historial>
     */
    #[ORM\OneToMany(targetEntity: Historial::class, mappedBy: 'mascota_id', orphanRemoval: true)]
    private Collection $historiales;

    public function __construct()
    {
        $this->historiales = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getEdad(): ?int
    {
        return $this->edad;
    }

    public function setEdad(int $edad): static
    {
        $this->edad = $edad;

        return $this;
    }

    public function getRaza(): ?string
    {
        return $this->raza;
    }

    public function setRaza(string $raza): static
    {
        $this->raza = $raza;

        return $this;
    }

    public function getAnimal(): ?string
    {
        return $this->animal;
    }

    public function setAnimal(string $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getGenero(): ?string
    {
        return $this->genero;
    }

    public function setGenero(string $genero): static
    {
        $this->genero = $genero;

        return $this;
    }

    public function getFicha(): ?string
    {
        return $this->ficha;
    }

    public function setFicha(string $ficha): static
    {
        $this->ficha = $ficha;

        return $this;
    }

    public function getClienteId(): ?Cliente
    {
        return $this->cliente_id;
    }

    public function setClienteId(?Cliente $cliente_id): static
    {
        $this->cliente_id = $cliente_id;

        return $this;
    }

    /**
     * @return Collection<int, Historial>
     */
    public function getHistoriales(): Collection
    {
        return $this->historiales;
    }

    public function addHistoriales(Historial $historiales): static
    {
        if (!$this->historiales->contains($historiales)) {
            $this->historiales->add($historiales);
            $historiales->setMascotaId($this);
        }

        return $this;
    }

    public function removeHistoriales(Historial $historiales): static
    {
        if ($this->historiales->removeElement($historiales)) {
            // set the owning side to null (unless already changed)
            if ($historiales->getMascotaId() === $this) {
                $historiales->setMascotaId(null);
            }
        }

        return $this;
    }
}
