<?php

namespace App\Entity;

use App\Repository\CartRepository;
use App\Entity\User;
use App\Entity\Art;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $cartRef = null;

    #[ORM\ManyToOne(inversedBy: 'carts')]
    #[ORM\JoinColumn(name:"uid", referencedColumnName:"uid")]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: Art::class)]
    private Collection $art;

    public function __construct()
    {
        $this->art = new ArrayCollection();
    }

    // #[ORM\ManyToOne(inversedBy: 'carts')]
    // private ?Art $artRef = null;
 
    // #[ORM\ManyToOne(inversedBy: 'carts')]
    // private ?User $uid = null;

    public function getCartRef(): ?int
    {
        return $this->cartRef;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Art>
     */
    public function getArt(): Collection
    {
        return $this->art;
    }

    // public function addArt(Art $art): static
    // {
    //     if (!$this->art->contains($art)) {
    //         $this->art->add($art);
    //         $art->setCart($this);
    //     }

    //     return $this;
    // }

    // public function removeArt(Art $art): static
    // {
    //     if ($this->art->removeElement($art)) {
    //         // set the owning side to null (unless already changed)
    //         if ($art->getCart() === $this) {
    //             $art->setCart(null);
    //         }
    //     }

    //     return $this;
    // }



}
