<?php
 
namespace App\Entity;
 
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReviewRepository;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
 
#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $reviewId= null;
 
    #[ORM\Column]
    #[Assert\NotBlank(message: 'Date is required')]
    private ?DateTime $datePublished;
 
    #[ORM\Column]
    #[Assert\NotBlank(message: 'rating is required')]
    private ?int $rating;
 
    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'Comment is required')]
    private ?string $comment;
 
 
    // #[ORM\ManyToOne(inversedBy: 'reviews')]
    // private ?User $uid;
 
    // #[ORM\ManyToOne(inversedBy: 'reviews')]
    // private $artRef;
 
    public function getReviewId(): ?int
    {
        return $this->reviewId;
    }
 
    public function getDatePublished(): ?\DateTimeInterface
    {
        return $this->datePublished;
    }
 
    public function setDatePublished(\DateTimeInterface $datePublished): static
    {
        $this->datePublished = $datePublished;
 
        return $this;
    }
 
    public function getRating(): ?int
    {
        return $this->rating;
    }
 
    public function setRating(int $rating): static
    {
        $this->rating = $rating;
 
        return $this;
    }
 
    public function getComment(): ?string
    {
        return $this->comment;
    }
 
    public function setComment(string $comment): static
    {
        $this->comment = $comment;
 
        return $this;
    }
 
    // public function getUid(): ?User
    // {
    //     return $this->uid;
    // }
 
    // public function setUid(?User $uid): static
    // {
    //     $this->uid = $uid;
 
    //     return $this;
    // }
 
    // public function getArtRef(): ?Art
    // {
    //     return $this->artRef;
    // }
 
    // public function setArtRef(?Art $artRef): static
    // {
    //     $this->artRef = $artRef;
 
    //     return $this;
    // }
 
 
}
