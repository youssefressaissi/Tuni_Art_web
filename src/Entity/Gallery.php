<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity
 */
class Gallery
{
    /**
     * @var int
     *
     * @ORM\Column(name="gallery_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $galleryId;

    /**
     * @var string
     *
     * @ORM\Column(name="gallery_name", type="string", length=100, nullable=false)
     */
    private $galleryName;

    /**
     * @var string
     *
     * @ORM\Column(name="gallery_description", type="string", length=100, nullable=false)
     */
    private $galleryDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="gallery_location", type="string", length=100, nullable=false)
     */
    private $galleryLocation;

    /**
     * @var int
     *
     * @ORM\Column(name="gallery_tel", type="integer", nullable=false)
     */
    private $galleryTel;

    /**
     * @var string
     *
     * @ORM\Column(name="operating_hours", type="string", length=100, nullable=false)
     */
    private $operatingHours;

    public function getGalleryId(): ?int
    {
        return $this->galleryId;
    }

    public function getGalleryName(): ?string
    {
        return $this->galleryName;
    }

    public function setGalleryName(string $galleryName): static
    {
        $this->galleryName = $galleryName;

        return $this;
    }

    public function getGalleryDescription(): ?string
    {
        return $this->galleryDescription;
    }

    public function setGalleryDescription(string $galleryDescription): static
    {
        $this->galleryDescription = $galleryDescription;

        return $this;
    }

    public function getGalleryLocation(): ?string
    {
        return $this->galleryLocation;
    }

    public function setGalleryLocation(string $galleryLocation): static
    {
        $this->galleryLocation = $galleryLocation;

        return $this;
    }

    public function getGalleryTel(): ?int
    {
        return $this->galleryTel;
    }

    public function setGalleryTel(int $galleryTel): static
    {
        $this->galleryTel = $galleryTel;

        return $this;
    }

    public function getOperatingHours(): ?string
    {
        return $this->operatingHours;
    }

    public function setOperatingHours(string $operatingHours): static
    {
        $this->operatingHours = $operatingHours;

        return $this;
    }


}
