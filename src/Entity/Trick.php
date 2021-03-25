<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @UniqueEntity(fields={"name"}, message="There is already a trick with this name")
 * @ORM\Table(name="`tricks`")
 */
class Trick
{
    use TimestampableEntity;

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
     *     min=2,
     *     max=255,
     *     minMessage="The trick name must be at least {{ limit }} characters long",
     *     maxMessage="The trick name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=5,
     *     minMessage="The trick description must be at least {{ limit }} characters long"
     * )
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private ?string $slug;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true)
     */
    private Collection $comments;

    /**
     * @ORM\OneToMany(targetEntity=TricksMedia::class, mappedBy="trick", orphanRemoval=true, cascade={"persist"})
     * @Assert\Valid
     */
    private Collection $tricksMedia;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
     */
    private Category $category;

    /**
     * The list of contributors of the Trick.
     *
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="tricks")
     */
    private Collection $users;

    /**
     * The author of the Trick.
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="authorTricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author = null;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->tricksMedia = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->comments->removeElement($comment) && $comment->getTrick() === $this) {
            $comment->setTrick(null);
        }

        return $this;
    }

    /**
     * @return Collection|TricksMedia[]
     */
    public function getTricksMedia(): Collection
    {
        return $this->tricksMedia;
    }

    public function addTricksMedium(TricksMedia $tricksMedium): self
    {
        if (!$this->tricksMedia->contains($tricksMedium)) {
            $this->tricksMedia[] = $tricksMedium;
            $tricksMedium->setTrick($this);
        }

        return $this;
    }

    public function removeTricksMedium(TricksMedia $tricksMedium): self
    {
        // set the owning side to null (unless already changed)
        if ($this->tricksMedia->removeElement($tricksMedium) && $tricksMedium->getTrick() === $this) {
            $tricksMedium->setTrick(null);
        }

        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addTrick($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeTrick($this);
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
