<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 *
 * @ORM\Table(name="review", indexes={@ORM\Index(name="fk_art_review", columns={"art_ref"}), @ORM\Index(name="fk_user_review", columns={"uid"})})
 * @ORM\Entity
 */
class Review
{
    /**
     * @var int
     *
     * @ORM\Column(name="review_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $reviewId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_published", type="date", nullable=false)
     */
    private $datePublished;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=512, nullable=false)
     */
    private $comment;

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
