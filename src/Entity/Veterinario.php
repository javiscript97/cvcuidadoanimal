<?php

namespace App\Entity;

use App\Repository\VeterinarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: VeterinarioRepository::class)]
#[Broadcast]
class Veterinario
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
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

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
}
