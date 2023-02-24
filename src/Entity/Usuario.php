<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[Vich\Uploadable]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface, Serializable
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

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 30)]
    private ?string $role = null;

    #[ORM\OneToMany(mappedBy: 'usuario_id', targetEntity: Valoracion::class)]
    private Collection $valoracions;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile = null;

    #[UploadableField(mapping: 'profiles', fileNameProperty: 'profile')]
    private ?File $fileProfile = null;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $updatedAt;

    #[ORM\ManyToMany(targetEntity: Vinilo::class, inversedBy: 'linkingUsers')]
    #[ORM\JoinTable(name:'user_save_vinil')]
    private Collection $savedVinils;

    /**
     * @return File|null
     */
    public function getFileProfile(): ?File
    {
        return $this->fileProfile;
    }

    /**
     * @param File|null $fileProfile
     */
    public function setFileProfile(?File $fileProfile): void
    {
        $this->fileProfile = $fileProfile;

        if($fileProfile){
            $this->updatedAt = new DateTime('now');
        }
    }

    public function __construct()
    {
        $this->valoracions = new ArrayCollection();
        $this->role = "ROLE_USER";
        $this->savedVinils = new ArrayCollection();
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
        // TODO: Implement getRoles() method.
        return array_unique([$this->getRole()]);
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

    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function serialize(): ?string
    {
        return serialize([
            $this->getId(),
            $this->getUsername(),
            $this->getPassword()
        ]);
    }

    public function unserialize(string $data)
    {
        list($this->id, $this->username, $this->password) = unserialize ($data, ['allowed_classes' => false]);
    }

    /**
     * @return Collection<int, Vinilo>
     */
    public function getSavedVinils(): Collection
    {
        return $this->savedVinils;
    }

    public function addSavedVinil(Vinilo $savedVinil): self
    {
        if (!$this->savedVinils->contains($savedVinil)) {
            $this->savedVinils->add($savedVinil);
        }

        return $this;
    }

    public function removeSavedVinil(Vinilo $savedVinil): self
    {
        $this->savedVinils->removeElement($savedVinil);

        return $this;
    }
}
