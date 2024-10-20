<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente implements UserInterface, PasswordAuthenticatedUserInterface
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
    private ?string $direccion = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $rol = null;

    #[ORM\Column]
    private ?int $telefono = null;

    /** 
     * @var Collection<int, Mascotas> 
     */
    #[ORM\OneToMany(targetEntity: Mascotas::class, mappedBy: 'cliente_id', orphanRemoval: true)]
    private Collection $mascotas;

    /** 
     * @var Collection<int, Chat> 
     */
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'cliente_id', orphanRemoval: true)]
    private Collection $chats;

    /** 
     * @var Collection<int, Citas> 
     */
    #[ORM\OneToMany(targetEntity: Citas::class, mappedBy: 'cliente_id', orphanRemoval: true)]
    private Collection $citas;

    public function __construct()
    {
        $this->mascotas = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->citas = new ArrayCollection();
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

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;
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

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;
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

    /**
     * @return Collection<int, Mascotas>
     */
    public function getMascotas(): Collection
    {
        return $this->mascotas;
    }

    public function addMascota(Mascotas $mascota): static
    {
        if (!$this->mascotas->contains($mascota)) {
            $this->mascotas->add($mascota);
            $mascota->setClienteId($this);
        }
        return $this;
    }

    public function removeMascota(Mascotas $mascota): static
    {
        if ($this->mascotas->removeElement($mascota)) {
            
            if ($mascota->getClienteId() === $this) {
                $mascota->setClienteId(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): static
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
            $chat->setClienteId($this);
        }
        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getClienteId() === $this) {
                $chat->setClienteId(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Citas>
     */
    public function getCitas(): Collection
    {
        return $this->citas;
    }

    public function addCita(Citas $cita): static
    {
        if (!$this->citas->contains($cita)) {
            $this->citas->add($cita);
            $cita->setClienteId($this);
        }
        return $this;
    }

    public function removeCita(Citas $cita): static
    {
        if ($this->citas->removeElement($cita)) {

            if ($cita->getClienteId() === $this) {
                $cita->setClienteId(null);
            }
        }
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return $this->mail;
    }

    public function eraseCredentials(): void
    {
        // Para borrar datos sensibles
    }
    public function getRoles(): array
    {
        return ['ROLE_USER']; // Devuelve un array con un rol de usuario por defecto

    }
}


