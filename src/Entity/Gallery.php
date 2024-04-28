<?php
 
namespace App\Entity;
 
use App\Repository\GalleryRepository;
use Symfony\Component\Validator\Constraints as Assert;
 
use Doctrine\ORM\Mapping as ORM;
 
 
#[ORM\Entity(repositoryClass: GalleryRepository::class)]
class Gallery
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private  ?int $galleryId= null;
 
 
    #[ORM\Column(length:100)]
    #[Assert\NotBlank(message: 'Name is required')]
    private ?string $galleryName = null;
 
    #[ORM\Column(length:100)]
    #[Assert\NotBlank(message: 'Description is required')]
    private ?string $galleryDescription= null ;
 
    #[ORM\Column(length:100)]
    #[Assert\NotBlank(message: 'Loation is required')]
    private ?string $galleryLocation= null;
 
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Number is required')]
    private ?int $galleryTel= null;
 
    #[ORM\Column(length:100)]
    private ?string $operatingHours;
    
 
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