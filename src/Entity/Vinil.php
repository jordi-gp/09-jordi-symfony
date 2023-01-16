<?php

namespace App\Entity;

use App\Repository\VinilRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VinilRepository::class)]
class Vinil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $preu = null;

    #[ORM\Column(length: 50)]
    private ?string $portada = null;

    #[ORM\Column(length: 250)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(inversedBy: 'vinils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artista $id_artista = null;

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

    public function getPreu(): ?int
    {
        return $this->preu;
    }

    public function setPreu(int $preu): self
    {
        $this->preu = $preu;

        return $this;
    }

    public function getPortada(): ?string
    {
        return $this->portada;
    }

    public function setPortada(string $portada): self
    {
        $this->portada = $portada;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getIdArtista(): ?Artista
    {
        return $this->id_artista;
    }

    public function setIdArtista(?Artista $id_artista): self
    {
        $this->id_artista = $id_artista;

        return $this;
    }
}
