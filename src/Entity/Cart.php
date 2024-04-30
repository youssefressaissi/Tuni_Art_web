<?php

namespace App\Entity;

use App\Entity\Art;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart", indexes={@ORM\Index(name="fk_art_cart", columns={"art_ref"}), @ORM\Index(name="fk_user_cart", columns={"uid"})})
 * @ORM\Entity
 */
class Cart
{
    /**
     * @var int
     *
     * @ORM\Column(name="cart_ref", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $cartRef;

    /**
     * @var \User|null
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="uid", referencedColumnName="uid")
     * })
     */
    private $uid;

    /**
     * @var \Art|null
     *
     * @ORM\ManyToOne(targetEntity="Art")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="art_ref", referencedColumnName="art_ref")
     * })
     */
    private $artRef;

    public function getCartRef(): ?int
    {
        return $this->cartRef;
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

    public function getArtRef(): ?Art
    {
        return $this->artRef;
    }

    public function setArtRef(?Art $artRef): static
    {
        $this->artRef = $artRef;

        return $this;
    }
}
