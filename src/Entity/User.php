<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    public const PROFILE_UPDATED = 'Profile successfully updated';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage="Your first name must be at least {{ limit }} characters long",
     *     maxMessage="Your first name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage="Your last name must be at least {{ limit }} characters long",
     *     maxMessage="Your last name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $lastName;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="Your username must be at least {{ limit }} characters long",
     *     maxMessage="Your username cannot be longer than {{ limit }} characters"
     * )
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     * @Assert\Email(
     *     message="The email '{{ value }}' is not a valid email."
     * )
     */
    private ?string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $avatar;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified = false;

    /**
     * The tricks modified by the User.
     * Used to get the contributors list of the tricks.
     *
     * @MaxDepth(1)
     * @ORM\ManyToMany(targetEntity=Trick::class, inversedBy="users")
     */
    private Collection $tricks;

    /**
     * The tricks created by the User.
     * Used to get the author of the tricks.
     *
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="author", orphanRemoval=true)
     * @OrderBy({"createdAt": "DESC"})
     */
    private Collection $authorTricks;

    /**
     * @MaxDepth(1)
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     * @OrderBy({"createdAt": "DESC"})
     */
    private Collection $comments;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->authorTricks = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getTricks(): Collection
    {
        return $this->tricks;
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        $this->tricks->removeElement($trick);

        return $this;
    }

    /**
     * @return Collection|Trick[]
     */
    public function getAuthorTricks(): Collection
    {
        return $this->authorTricks;
    }

    public function addAuthorTrick(Trick $authorTrick): self
    {
        if (!$this->authorTricks->contains($authorTrick)) {
            $this->authorTricks[] = $authorTrick;
            $authorTrick->setAuthor($this);
        }

        return $this;
    }

    public function removeAuthorTrick(Trick $authorTrick): self
    {
        // set the owning side to null (unless already changed)
        if ($this->authorTricks->removeElement($authorTrick) && $authorTrick->getAuthor() === $this) {
            $authorTrick->setAuthor(null);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->comments->removeElement($comment) && $comment->getAuthor() === $this) {
            $comment->setAuthor(null);
        }

        return $this;
    }
}
