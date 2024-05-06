<?php

namespace App\Entity;

use App\Repository\ArtRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: ArtRepository::class)]
class Art
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $artRef = null;

    #[ORM\Column(length: 512)]
    private ?string $artTitle = null;

    #[ORM\Column]
    private ?float $artPrice = null;

    #[ORM\Column]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $creation = null;

    #[ORM\Column(length: 512)]
    private ?string $description = null;

    #[ORM\Column(length: 512)]
    private ?string $style = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $imageId;

    #[ORM\Column(length: 250, nullable: true)]
    private ?string $musicPath;

    #[ORM\Column(nullable: true)]
    private ?int $artViews;

    #[ORM\Column]
    private ?bool $isavailable = true;

    #[ORM\ManyToOne(inversedBy: 'art')]
    #[ORM\JoinColumn(name:"artist_id", referencedColumnName:"uid")]
    private ?User $artist = null;

    #[ORM\OneToMany(mappedBy: 'art', targetEntity: Auction::class)]
    private Collection $auctions;

    #[ORM\ManyToOne(inversedBy: 'art')]
    private ?Cart $cart = null;

    public function __construct()
    {
        $this->auctions = new ArrayCollection();
    }

    // #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'arts')]
    // private ?User $artist = null;

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

    // public function getArtist(): ?User
    // {
    //     return $this->artist;
    // }

    // public function setArtist(?User $artist): static
    // {
    //     $this->artist = $artist;

    //     return $this;
    // }

    public function getArtist(): ?User
    {
        return $this->artist;
    }

    public function setArtist(?User $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return Collection<int, Auction>
     */
    public function getAuctions(): Collection
    {
        return $this->auctions;
    }

    public function addAuction(Auction $auction): static
    {
        if (!$this->auctions->contains($auction)) {
            $this->auctions->add($auction);
            $auction->setArt($this);
        }

        return $this;
    }

    public function removeAuction(Auction $auction): static
    {
        if ($this->auctions->removeElement($auction)) {
            // set the owning side to null (unless already changed)
            if ($auction->getArt() === $this) {
                $auction->setArt(null);
            }
        }

        return $this;
    }

    // public function getCart(): ?Cart
    // {
    //     return $this->cart;
    // }

    // public function setCart(?Cart $cart): static
    // {
    //     $this->cart = $cart;

    //     return $this;
    // }


}
