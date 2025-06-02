<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: "usuario")]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "tipo", type: "string")]
#[ORM\DiscriminatorMap([
    "cliente" => Cliente::class,
    "veterinario" => Veterinario::class,
    "administrador" => Administrador::class,
])]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $telefono = null;

    #[ORM\Column(nullable: true)]
    private ?string $restauraToken = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $restauraTokenTiempo = null;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getTelefono(): ?int
    {
        return $this->telefono;
    }

    public function setTelefono(int $telefono): static
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->mail;
    }

    public function eraseCredentials(): void
    {
        // Si almacenas datos sensibles temporalmente, límpiar aquí
    }

    public function getRoles(): array
    {
        return ['ROLE_USER']; // Rol por defecto, puede ser sobreescrito por clases hijas
    }

    public function getResetToken(): ?string
    {
        return $this->restauraToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->restauraToken = $resetToken;
        return $this;
    }

    public function getRestauraTokenTiempo(): ?\DateTimeInterface
    {
        return $this->restauraTokenTiempo;
    }

    public function setRestauraTokenTiempo(?\DateTimeInterface $duracion): self
    {
        $this->restauraTokenTiempo = $duracion;
        return $this;
    }
}
