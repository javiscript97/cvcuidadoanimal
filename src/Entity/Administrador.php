<?php

namespace App\Entity;

use App\Repository\AdministradorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdministradorRepository::class)]
class Administrador extends Usuario
{

    #[ORM\Column(length: 255)]
    private ?string $rol = null;

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }
    public function getRoles(): array
    {
        return ['ROLE_ADMIN']; 
    }
}
