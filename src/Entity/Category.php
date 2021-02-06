<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity(fields={"name"}, message="There is already a category with this name")
 * @ORM\Table(name="`categories`")
 */
class Category
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
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage="The category name must be at least {{ limit }} characters long",
     *     maxMessage="The category name cannot be longer than {{ limit }} characters"
     * )
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=Trick::class, mappedBy="category")
     */
    private Collection $tricks;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    /**
     * Temporary fix for "Typed property must not be accessed before initialization" error.
     */
    public function __sleep(): array
    {
        return [];
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
            $trick->setCategory($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        // set the owning side to null (unless already changed)
        if ($this->tricks->removeElement($trick) && $trick->getCategory() === $this) {
            $trick->setCategory(null);
        }

        return $this;
    }
}
