<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=MediaRepository::class)
 */
class Media
{
    use TimestampableEntity;

    public const IMAGE = 'image';
    public const VIDEO = 'video';
    public const YOUTUBE_VIDEO = 'youtube';
    public const VIMEO_VIDEO = 'vimeo';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $altText;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="medias")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Trick $trick;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $type;

    /**
     * @ORM\OneToOne(targetEntity=Trick::class, mappedBy="coverImage", cascade={"persist", "remove"})
     */
    private ?Trick $mainTrick;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAltText(): ?string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): self
    {
        $this->altText = $altText;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMainTrick(): ?Trick
    {
        return $this->mainTrick;
    }

    public function setMainTrick(Trick $mainTrick): self
    {
        // set the owning side of the relation if necessary
        if ($mainTrick->getCoverImage() !== $this) {
            $mainTrick->setCoverImage($this);
        }

        $this->mainTrick = $mainTrick;

        return $this;
    }
}
