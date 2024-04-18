<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $eventId = null;

    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'title event is required')]
    private ?string $eventTitle = null;

    #[ORM\Column(length: 512)]
    #[Assert\NotBlank(message: 'category is required')]
    private ?string $category = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'event date is required')]
    private ?\DateTimeInterface $eventDate = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'duration is required')]
    #[Assert\GreaterThanOrEqual(value: 0, message: 'Duration must be a non-negative number.')]    private ?int $duration = null;
    #[ORM\Column]
    private ?int $aid = null;

    // #[ORM\ManyToOne(inversedBy: 'events')]
    // #[ORM\JoinColumn(nullable: false)]
    // private ?User $user = null;

    // #[ORM\ManyToOne(inversedBy: 'events')]
    // private ?User $aid;

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function getEventTitle(): ?string
    {
        return $this->eventTitle;
    }

    public function setEventTitle(string $eventTitle): static
    {
        $this->eventTitle = $eventTitle;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): static
    {
        $this->eventDate = $eventDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }
    public function getaid(): ?int
    {
        return $this->aid;
    }

    public function setaid(int $aid): static
    {
        $this->aid = $aid;

        return $this;
    }

    // public function getaid(): ?User
    // {
    //     return $this->aid;
    // }

    // public function setaid(?User $aid): static
    // {
    //     $this->aid = $aid;

    //     return $this;
    // }

//    public function getUser(): ?User
  //  {
  //  return $this->user;
    //}

//    public function setUser(?User $user): static
  //  {
    //    $this->user = $user;

      //  return $this;
    //}


}
