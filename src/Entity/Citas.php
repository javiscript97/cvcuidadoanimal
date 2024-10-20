<?php

namespace App\Entity;

use App\Repository\CitasRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: CitasRepository::class)]
#[Broadcast]
class Citas
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\ManyToOne(inversedBy: 'citas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cliente $cliente_id = null;

    #[ORM\ManyToOne(inversedBy: 'citas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Veterinario $vet_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

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

    public function getVetId(): ?Veterinario
    {
        return $this->vet_id;
    }

    public function setVetId(?Veterinario $vet_id): static
    {
        $this->vet_id = $vet_id;

        return $this;
    }

    public function getClienteNombre(): ?string
{
    return $this->cliente_id ? $this->cliente_id->getNombre() : null;
}
public function getVetNombre(): ?string
{
    return $this->vet_id ? $this->vet_id->getNombre() : null;
}
}
