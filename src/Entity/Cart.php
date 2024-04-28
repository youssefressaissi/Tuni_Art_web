<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "cart_ref", type: "integer")]
    private ?int $cartRef = null;

    #[ORM\Column(name: "uid", type: "integer", nullable: true)]
    private ?int $uid = null;

    #[ORM\Column(name: "art_ref", type: "integer", nullable: true)]
    private ?int $artRef = null;

    public function getCartRef(): ?int
    {
        return $this->cartRef;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getArtRef(): ?int
    {
        return $this->artRef;
    }

    public function setArtRef(?int $artRef): self
    {
        $this->artRef = $artRef;

        return $this;
    }
}
