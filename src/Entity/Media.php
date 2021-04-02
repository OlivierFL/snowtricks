<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    public const UNKNOWN = 'unknown';
    public const UNKNOWN_VIDEO_TITLE = 'Unknown video title';
    public const MEDIA_UPDATED = 'Media updated';

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
     * @ORM\OneToMany(targetEntity=TricksMedia::class, mappedBy="media", orphanRemoval=true, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Collection $tricksMedia;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $type;

    public function __construct()
    {
        $this->tricksMedia = new ArrayCollection();
    }

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

    public function setAltText(?string $altText): self
    {
        $this->altText = $altText;

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
            $tricksMedium->setMedia($this);
        }

        return $this;
    }

    public function removeTricksMedium(TricksMedia $tricksMedium): self
    {
        // set the owning side to null (unless already changed)
        if ($this->tricksMedia->removeElement($tricksMedium) && $tricksMedium->getMedia() === $this) {
            $tricksMedium->setMedia(null);
        }

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
}
