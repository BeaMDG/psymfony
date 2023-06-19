<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    const REGISTRO_EXITOSO = 'Registro exitoso. ¡Bienvenido!';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 10)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Comentarios::class)]
    private Collection $comentarios_id;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Posts::class)]
    private Collection $Posts;

    public function __construct()
    {
        $this->comentarios_id = new ArrayCollection();
        $this->Posts = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Comentarios>
     */
    public function getComentariosId(): Collection
    {
        return $this->comentarios_id;
    }

    public function addComentariosId(Comentarios $comentariosId): static
    {
        if (!$this->comentarios_id->contains($comentariosId)) {
            $this->comentarios_id->add($comentariosId);
            $comentariosId->setUser($this);
        }

        return $this;
    }

    public function removeComentariosId(Comentarios $comentariosId): static
    {
        if ($this->comentarios_id->removeElement($comentariosId)) {
            // set the owning side to null (unless already changed)
            if ($comentariosId->getUser() === $this) {
                $comentariosId->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Posts>
     */
    public function getPosts(): Collection
    {
        return $this->Posts;
    }

    public function addPost(Posts $post): static
    {
        if (!$this->Posts->contains($post)) {
            $this->Posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Posts $post): static
    {
        if ($this->Posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }
}
