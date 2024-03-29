<?php

namespace App\Entity;

use App\Repository\ViniloRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: ViniloRepository::class)]
#[Vich\Uploadable]
class Vinilo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 50)]
    private ?string $cover = null;

    #[Vich\UploadableField(mapping: 'covers', fileNameProperty: 'cover')]
    private ?File $fileCover = null;

    #[ORM\Column(length: 250)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'vinilos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artista $artista = null;

    #[ORM\Column]
    private ?float $rating = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'vinilo_id', targetEntity: Valoracion::class)]
    private Collection $valoracions;

    #[ORM\ManyToMany(targetEntity: Usuario::class, mappedBy: 'savedVinils')]
    private Collection $linkingUsers;

    public function __construct()
    {
        $this->valoracions = new ArrayCollection();
        $this->linkingUsers = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): self
    {
        $this->cover = $cover;

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

    public function getArtista(): ?Artista
    {
        return $this->artista;
    }

    public function setArtista(?Artista $artista): self
    {
        $this->artista = $artista;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Valoracion>
     */
    public function getValoracions(): Collection
    {
        return $this->valoracions;
    }

    public function addValoracion(Valoracion $valoracion): self
    {
        if (!$this->valoracions->contains($valoracion)) {
            $this->valoracions->add($valoracion);
            $valoracion->setViniloId($this);
        }

        return $this;
    }

    public function removeValoracion(Valoracion $valoracion): self
    {
        if ($this->valoracions->removeElement($valoracion)) {
            // set the owning side to null (unless already changed)
            if ($valoracion->getViniloId() === $this) {
                $valoracion->setViniloId(null);
            }
        }

        return $this;
    }

    public function getFileCover(): ?File
    {
        return $this->fileCover;
    }

    public function setFileCover(File $fileCover): self
    {
        $this->fileCover = $fileCover;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getLinkingUsers(): Collection
    {
        return $this->linkingUsers;
    }

    public function addLinkingUser(Usuario $linkingUser): self
    {
        if (!$this->linkingUsers->contains($linkingUser)) {
            $this->linkingUsers->add($linkingUser);
            $linkingUser->addSavedVinil($this);
        }

        return $this;
    }

    public function removeLinkingUser(Usuario $linkingUser): self
    {
        if ($this->linkingUsers->removeElement($linkingUser)) {
            $linkingUser->removeSavedVinil($this);
        }

        return $this;
    }
}
