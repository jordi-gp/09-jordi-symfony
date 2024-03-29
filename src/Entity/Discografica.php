<?php

namespace App\Entity;

use App\Repository\DiscograficaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscograficaRepository::class)]
class Discografica
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'id_discografica', targetEntity: Artista::class)]
    private Collection $artistas;

    public function __construct()
    {
        $this->artistas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Artista>
     */
    public function getArtistas(): Collection
    {
        return $this->artistas;
    }

    public function addArtista(Artista $artista): self
    {
        if (!$this->artistas->contains($artista)) {
            $this->artistas->add($artista);
            $artista->setIdDiscografica($this);
        }

        return $this;
    }

    public function removeArtista(Artista $artista): self
    {
        if ($this->artistas->removeElement($artista)) {
            // set the owning side to null (unless already changed)
            if ($artista->getIdDiscografica() === $this) {
                $artista->setIdDiscografica(null);
            }
        }

        return $this;
    }
}
