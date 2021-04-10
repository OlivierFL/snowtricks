<?php

namespace App\Entity;

use App\Repository\TricksMediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TricksMediaRepository::class)
 */
class TricksMedia
{
    public const COVER_IMAGE_UPDATED = 'Cover image updated';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Trick::class, inversedBy="tricksMedia")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Trick $trick;

    /**
     * @MaxDepth(1)
     * @ORM\ManyToOne(targetEntity=Media::class, inversedBy="tricksMedia", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid
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
