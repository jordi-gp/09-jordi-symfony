<?php

namespace App\Entity;

use App\Repository\ArtistaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArtistaRepository::class)]
class Artista
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $nombre = null;

    #[ORM\ManyToOne(inversedBy: 'artistas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Discografica $id_discografica = null;

    #[ORM\OneToMany(mappedBy: 'id_artista', targetEntity: Vinil::class)]
    private Collection $vinils;

    public function __construct()
    {
        $this->vinils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getIdDiscografica(): ?Discografica
    {
        return $this->id_discografica;
    }

    public function setIdDiscografica(?Discografica $id_discografica): self
    {
        $this->id_discografica = $id_discografica;

        return $this;
    }

    /**
     * @return Collection<int, Vinil>
     */
    public function getVinils(): Collection
    {
        return $this->vinils;
    }

    public function addVinil(Vinil $vinil): self
    {
        if (!$this->vinils->contains($vinil)) {
            $this->vinils->add($vinil);
            $vinil->setIdArtista($this);
        }

        return $this;
    }

    public function removeVinil(Vinil $vinil): self
    {
        if ($this->vinils->removeElement($vinil)) {
            // set the owning side to null (unless already changed)
            if ($vinil->getIdArtista() === $this) {
                $vinil->setIdArtista(null);
            }
        }

        return $this;
    }
}
