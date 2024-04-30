<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Art
 *
 * @ORM\Table(name="art", indexes={@ORM\Index(name="fk_user_art", columns={"artist_id"})})
 * @ORM\Entity
 */
class Art
{
    /**
     * @var int
     *
     * @ORM\Column(name="art_ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $artRef;

    /**
     * @var string
     *
     * @ORM\Column(name="art_title", type="string", length=512, nullable=false)
     */
    private $artTitle;

    /**
     * @var float
     *
     * @ORM\Column(name="art_price", type="float", precision=10, scale=0, nullable=false)
     */
    private $artPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=512, nullable=false)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="date", nullable=false)
     */
    private $creation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=512, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="style", type="string", length=512, nullable=false)
     */
    private $style;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_id", type="string", length=300, nullable=true)
     */
    private $imageId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="music_path", type="string", length=250, nullable=true)
     */
    private $musicPath;

    /**
     * @var int|null
     *
     * @ORM\Column(name="art_views", type="integer", nullable=true)
     */
    private $artViews;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAvailable", type="boolean", nullable=false, options={"default"="1"})
     */
    private $isavailable = true;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="artist_id", referencedColumnName="uid")
     * })
     */
    private $artist;

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

    public function getArtist(): ?User
    {
        return $this->artist;
    }

    public function setArtist(?User $artist): static
    {
        $this->artist = $artist;

        return $this;
    }


}
