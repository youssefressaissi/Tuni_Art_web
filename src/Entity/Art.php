<?php

namespace App\Entity;

use App\Repository\ArtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtRepository::class)]
class Art
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $artRef = null;

    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'title is required')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]+$/',
        message: 'The title can only contain letters, numbers, and spaces.'
    )]
    private ?string $artTitle = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'price is required')]
    #[Assert\GreaterThan(
        value: 0,
        message: 'The price must be greater than zero.'
    )]
    #[Assert\Regex(
        pattern: '/^\d*\.?\d+$/',
        message: 'The price can only contain digits and a decimal point.'
    )]
    private ?float $artPrice = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'type is required')]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]

    private ?\DateTimeInterface $creation = null;

    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'description is required')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z0-9\s]+$/',
        message: 'The Description can only contain letters, numbers, and spaces.'
    )]
    private ?string $description = null;

    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'style is required')]
    private ?string $style = null;
    
    #[ORM\Column(length: 300, nullable: true)]
    private ?string $imageId;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $musicPath;

    #[ORM\Column(nullable: true)]
    private ?int $artViews;

    #[ORM\Column]
    private ?bool $isavailable = true;

    #[ORM\Column(nullable: false)]
    private int $artist_id;

    public function getArtistId(): ?int
    {
        return $this->artist_id;
    }

    public function setArtistId(int $artist_id): static
    {
        $this->artist_id = $artist_id;
        return $this;
    }
    

    public function getArtRef(): ?int
    {
        return $this->artRef;
    }

    public function getArtTitle(): ?string
    {
        return $this->artTitle;
    }

    public function setArtTitle(string $artTitle): static
    {
        $this->artTitle = $artTitle;

        return $this;
    }

    public function getArtPrice(): ?float
    {
        return $this->artPrice;
    }

    public function setArtPrice(float $artPrice): static
    {
        $this->artPrice = $artPrice;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): static
    {
        $this->creation = $creation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStyle(): ?string
    {
        return $this->style;
    }

    public function setStyle(string $style): static
    {
        $this->style = $style;

        return $this;
    }

    public function getImageId(): ?string
    {
        return $this->imageId;
    }

    public function setImageId(?string $imageId): static
    {
        $this->imageId = $imageId;

        return $this;
    }

    public function getMusicPath(): ?string
    {
        return $this->musicPath;
    }

    public function setMusicPath(?string $musicPath): static
    {
        $this->musicPath = $musicPath;

        return $this;
    }

    public function getArtViews(): ?int
    {
        return $this->artViews;
    }

    public function setArtViews(?int $artViews): static
    {
        $this->artViews = $artViews;

        return $this;
    }

    public function isIsavailable(): ?bool
    {
        return $this->isavailable;
    }

    public function setIsavailable(bool $isavailable): static
    {
        $this->isavailable = $isavailable;

        return $this;
    }

}
