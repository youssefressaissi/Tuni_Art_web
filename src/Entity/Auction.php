<?php

namespace App\Entity;

use App\Repository\AuctionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuctionRepository::class)]
class Auction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $auctionRef = null;

    #[ORM\Column(length: 512)]
    private ?string $auctionName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?float $threshold = null;

    #[ORM\Column(length: 1570)]
    private ?string $status = 'Pending';

    #[ORM\Column]
    private ?float $highestBid;

    #[ORM\Column]
    private ?int $currentwinnerId;

    #[ORM\Column]
    private ?int $interactions = 0;

    #[ORM\ManyToOne(inversedBy: 'auctions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Art $art = null;

    #[ORM\ManyToOne(inversedBy: 'auctions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // #[ORM\ManyToOne(inversedBy: 'auctions')]
    // private ?Art $artRef;

    // #[ORM\ManyToOne(inversedBy: 'auctions')]
    // private ?User $uid;

    public function getAuctionRef(): ?int
    {
        return $this->auctionRef;
    }

    public function getAuctionName(): ?string
    {
        return $this->auctionName;
    }

    public function setAuctionName(string $auctionName): static
    {
        $this->auctionName = $auctionName;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getThreshold(): ?float
    {
        return $this->threshold;
    }

    public function setThreshold(float $threshold): static
    {
        $this->threshold = $threshold;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getHighestBid(): ?float
    {
        return $this->highestBid;
    }

    public function setHighestBid(?float $highestBid): static
    {
        $this->highestBid = $highestBid;

        return $this;
    }

    public function getCurrentwinnerId(): ?int
    {
        return $this->currentwinnerId;
    }

    public function setCurrentwinnerId(?int $currentwinnerId): static
    {
        $this->currentwinnerId = $currentwinnerId;

        return $this;
    }

    public function getInteractions(): ?int
    {
        return $this->interactions;
    }

    public function setInteractions(int $interactions): static
    {
        $this->interactions = $interactions;

        return $this;
    }

    // public function getArtRef(): ?Art
    // {
    //     return $this->artRef;
    // }

    // public function setArtRef(?Art $artRef): static
    // {
    //     $this->artRef = $artRef;

    //     return $this;
    // }

    // public function getUid(): ?User
    // {
    //     return $this->uid;
    // }

    // public function setUid(?User $uid): static
    // {
    //     $this->uid = $uid;

    //     return $this;
    // }

    public function getArt(): ?Art
    {
        return $this->art;
    }

    public function setArt(?Art $art): static
    {
        $this->art = $art;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }


}
