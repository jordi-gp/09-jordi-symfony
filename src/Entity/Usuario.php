<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(
        message: "S'ha d'emplenar aquest camp"
    )]
    #[Assert\Length(
        min: 5,
        max: 30,
        minMessage: "El nom ha de contindre almenys 5 caracters",
        maxMessage: "El nom no pot contindre més de 30 caracters"
    )]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(
        message: "S'ha d'emplenar aquest camp"
    )]
    #[Assert\Length(
        min: 5,
        max: 30,
        minMessage: "El nom d'usuari ha de contindre almenys 5 caracters",
        maxMessage: "El nom d'usuari no pot contindre més de 30 caracters"
    )]
    private ?string $username = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(
        message: "S'ha d'emplenar aquest camp"
    )]
    #[Assert\Length(
        min: 10,
        max: 50,
        minMessage: "El correu ha de contindre almenys 10 caracters",
        maxMessage: "El correu no pot contindre més de 50 caracters"
    )]
    #[Assert\Email(
        message: "El correu indicat no es correcte"
    )]
    private ?string $email = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(
        message: "S'ha d'emplenar aquest camp"
    )]
    #[Assert\Length(
        min: 6,
        max: 30,
        minMessage: "La contrasenya ha de contindre almenys 6 caracters",
        maxMessage: "La contrasenya no pot contindre més de 30 caracters"
    )]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'usuario_id', targetEntity: Valoracion::class)]
    private Collection $valoracions;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank]
    #[Assert\GreaterThan("today")]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->valoracions = new ArrayCollection();
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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

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
            $valoracion->setUsuarioId($this);
        }

        return $this;
    }

    public function removeValoracion(Valoracion $valoracion): self
    {
        if ($this->valoracions->removeElement($valoracion)) {
            // set the owning side to null (unless already changed)
            if ($valoracion->getUsuarioId() === $this) {
                $valoracion->setUsuarioId(null);
            }
        }

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

    public function getRoles(): array
    {
        return [$this->getRole()];
    }

    public function getSalt(): string
    {
        // TODO: Implement getSalt() method.
        return "";
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): ?string
    {
        return $this->getUsername();
    }
}
