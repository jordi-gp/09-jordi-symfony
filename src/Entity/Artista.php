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

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 250)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'artistas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Discografica $discografica = null;

    #[ORM\OneToMany(mappedBy: 'artista', targetEntity: Vinilo::class)]
    private Collection $vinilos;

    public function __construct()
    {
        $this->vinilos = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getDiscografica(): ?Discografica
    {
        return $this->discografica;
    }

    public function setDiscografica(?Discografica $discografica): self
    {
        $this->discografica = $discografica;

        return $this;
    }

    /**
     * @return Collection<int, Vinilo>
     */
    public function getVinilos(): Collection
    {
        return $this->vinilos;
    }

    public function addVinilo(Vinilo $vinilo): self
    {
        if (!$this->vinilos->contains($vinilo)) {
            $this->vinilos->add($vinilo);
            $vinilo->setIdArtista($this);
        }

        return $this;
    }

    public function removeVinilo(Vinilo $vinilo): self
    {
        if ($this->vinilos->removeElement($vinilo)) {
            // set the owning side to null (unless already changed)
            if ($vinilo->getIdArtista() === $this) {
                $vinilo->setIdArtista(null);
            }
        }

        return $this;
    }
}
