<?php

namespace App\Entity;

use App\Repository\TricksMediaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TricksMediaRepository::class)
 */
class TricksMedia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="tricksMedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Trick $trick;

    /**
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="tricksMedia", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Media $media;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private ?bool $isCoverImage = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }

    public function getIsCoverImage(): ?bool
    {
        return $this->isCoverImage;
    }

    public function setIsCoverImage(bool $isCoverImage): self
    {
        $this->isCoverImage = $isCoverImage;

        return $this;
    }
}
