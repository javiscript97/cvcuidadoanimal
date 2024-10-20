<?php

namespace App\Entity;

use App\Repository\HistorialRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: HistorialRepository::class)]
#[Broadcast]
class Historial
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descripcion = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $fecha = null;

    #[ORM\ManyToOne(inversedBy: 'historiales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mascotas $mascota_id = null;

    #[ORM\ManyToOne(inversedBy: 'historiales')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Veterinario $vet_id = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getMascotaId(): ?Mascotas
    {
        return $this->mascota_id;
    }

    public function setMascotaId(?Mascotas $mascota_id): static
    {
        $this->mascota_id = $mascota_id;

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

public function getMascotaNombre(): ?string
{
    return $this->mascota_id ? $this->mascota_id->getNombre() : null;
}
public function getVetNombre(): ?string
{
    return $this->vet_id ? $this->vet_id->getNombre() : null;
}
}
