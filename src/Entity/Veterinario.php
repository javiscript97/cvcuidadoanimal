<?php

namespace App\Entity;

use App\Entity\Chat;
use App\Entity\Citas;
use App\Entity\Historial;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\VeterinarioRepository;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: VeterinarioRepository::class)]
#[Broadcast]
class Veterinario extends Usuario
{

    #[ORM\Column]
    private ?int $edad = null;

    #[ORM\Column(length: 255)]
    private ?string $rol = null;

    #[ORM\Column(length: 255)]
    private ?string $especialidad = null;

    /**
     * @var Collection<int, Chat>
     */
    #[ORM\OneToMany(targetEntity: Chat::class, mappedBy: 'vet_id', orphanRemoval: true)]
    private Collection $chats;

    /**
     * @var Collection<int, Historial>
     */
    #[ORM\OneToMany(targetEntity: Historial::class, mappedBy: 'vet_id')]
    private Collection $historiales;

    /**
     * @var Collection<int, Citas>
     */
    #[ORM\OneToMany(targetEntity: Citas::class, mappedBy: 'vet_id', orphanRemoval: true)]
    private Collection $citas;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
        $this->historiales = new ArrayCollection();
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


    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getEspecialidad(): ?string
    {
        return $this->especialidad;
    }

    public function setEspecialidad(string $especialidad): static
    {
        $this->especialidad = $especialidad;

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
            $chat->setVetId($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): static
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getVetId() === $this) {
                $chat->setVetId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Historial>
     */
    public function getHistoriales(): Collection
    {
        return $this->historiales;
    }

    public function addHistoriale(Historial $historiale): static
    {
        if (!$this->historiales->contains($historiale)) {
            $this->historiales->add($historiale);
            $historiale->setVetId($this);
        }

        return $this;
    }

    public function removeHistoriale(Historial $historiale): static
    {
        if ($this->historiales->removeElement($historiale)) {
            // set the owning side to null (unless already changed)
            if ($historiale->getVetId() === $this) {
                $historiale->setVetId(null);
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
            $cita->setVetId($this);
        }

        return $this;
    }

    public function removeCita(Citas $cita): static
    {
        if ($this->citas->removeElement($cita)) {
            // set the owning side to null (unless already changed)
            if ($cita->getVetId() === $this) {
                $cita->setVetId(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_VET']; // Devuelve un array con un rol de usuario por defecto

    }

}
