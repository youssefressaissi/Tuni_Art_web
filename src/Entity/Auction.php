<?php

namespace App\Entity;

use App\Entity\Art;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Auction
 *
 * @ORM\Table(name="auction", indexes={@ORM\Index(name="fk_art_auction", columns={"art_ref"}), @ORM\Index(name="fk_artist_auction", columns={"uid"})})
 * @ORM\Entity
 */
class Auction
{
    /**
     * @var int
     *
     * @ORM\Column(name="auction_ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $auctionRef;

    /**
     * @var string
     *
     * @ORM\Column(name="auction_name", type="string", length=512, nullable=false)
     */
    private $auctionName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="date", nullable=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="date", nullable=false)
     */
    private $endDate;

    /**
     * @var float
     *
     * @ORM\Column(name="threshold", type="float", precision=10, scale=0, nullable=false)
     */
    private $threshold;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1570, nullable=false, options={"default"="Pending"})
     */
    private $status = 'Pending';

    /**
     * @var float|null
     *
     * @ORM\Column(name="Highest_bid", type="float", precision=10, scale=0, nullable=true)
     */
    private $highestBid;

    /**
     * @var int|null
     *
     * @ORM\Column(name="currentWinner_id", type="integer", nullable=true)
     */
    private $currentwinnerId;

    /**
     * @var int
     *
     * @ORM\Column(name="interactions", type="integer", nullable=false)
     */
    private $interactions = '0';

    /**
     * @var \Art|null
     *
     * @ORM\ManyToOne(targetEntity="Art")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="art_ref", referencedColumnName="art_ref")
     * })
     */
    private $artRef;

    /**
     * @var \User|null
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     * })
     */
    private $uid;

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

    public function getArtRef(): ?Art
    {
        return $this->artRef;
    }

    public function setArtRef(?Art $artRef): static
    {
        $this->artRef = $artRef;

        return $this;
    }

    public function getUid(): ?User
    {
        return $this->uid;
    }

    public function setUid(?User $uid): static
    {
        $this->uid = $uid;

        return $this;
    }
}
