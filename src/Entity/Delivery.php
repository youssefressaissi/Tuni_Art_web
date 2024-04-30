<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Delivery
 *
 * @ORM\Table(name="delivery", indexes={@ORM\Index(name="fk_order_delivery", columns={"order_id"}), @ORM\Index(name="fk_agency_delivery", columns={"agency_id"})})
 * @ORM\Entity(repositoryClass=App\Repository\DeliveryRepository::class)
 */
class Delivery
{
    /**
     * @var int
     *
     * @ORM\Column(name="delivery_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $deliveryId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="estimated_date", type="date", nullable=false)
     */
    private $estimatedDate;

    /**
     * @var float
     *
     * @ORM\Column(name="delivery_fees", type="float", precision=10, scale=0, nullable=false)
     */
    private $deliveryFees;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", length=512, nullable=false)
     */
    private $destination;

    /**
     * @var bool
     *
     * @ORM\Column(name="state", type="boolean", nullable=false)
     */
    private $state;

    /**
     * @var \DeliveryAgency|null
     *
     * @ORM\ManyToOne(targetEntity="DeliveryAgency")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agency_id", referencedColumnName="agency_id")
     * })
     */
    private $agency;

    /**
     * @var \Orders|null
     *
     * @ORM\ManyToOne(targetEntity="Orders")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_id", referencedColumnName="order_id")
     * })
     */
    private $order;

    public function getDeliveryId(): ?int
    {
        return $this->deliveryId;
    }

    public function getEstimatedDate(): ?\DateTimeInterface
    {
        return $this->estimatedDate;
    }

    public function setEstimatedDate(\DateTimeInterface $estimatedDate): static
    {
        $this->estimatedDate = $estimatedDate;

        return $this;
    }

    public function getDeliveryFees(): ?float
    {
        return $this->deliveryFees;
    }

    public function setDeliveryFees(float $deliveryFees): static
    {
        $this->deliveryFees = $deliveryFees;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getAgency(): ?DeliveryAgency
    {
        return $this->agency;
    }

    public function setAgency(?DeliveryAgency $agency): static
    {
        $this->agency = $agency;

        return $this;
    }

    public function getOrder(): ?Orders
    {
        return $this->order;
    }

    public function setOrder(?Orders $order): static
    {
        $this->order = $order;

        return $this;
    }
}
