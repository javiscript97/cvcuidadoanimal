<?php

namespace App\Entity;

use App\Repository\ClienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClienteRepository::class)]
class Cliente extends Usuario
{
 
    #[ORM\Column]
    private ?int $edad = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(length: 255)]
    private ?string $rol = "ROLE_USER";

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
        if(!$this->rol){
            return ['ROLE_USER']; // Devuelve un array con un rol de usuario por defecto
        }

        return [$this->rol];

    }

}


