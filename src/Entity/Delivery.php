<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
class Delivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $deliveryId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $estimatedDate = null;

    #[ORM\Column]
    private ?float $deliveryFees = null;

    #[ORM\Column(length: 512)]
    private ?string $destination = null;

    #[ORM\Column]
    private ?bool $state = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeliveryAgency $agency = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order = null;

    // #[ORM\ManyToOne(inversedBy: 'deliveries')]
    // private ?Order $order;

    // #[ORM\ManyToOne(inversedBy: 'deliveries')]
    // private ?DeliveryAgency $agency;

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

    // public function getOrder(): ?Order
    // {
    //     return $this->order;
    // }

    // public function setOrder(?Order $order): static
    // {
    //     $this->order = $order;

    //     return $this;
    // }

    // public function getAgency(): ?DeliveryAgency
    // {
    //     return $this->agency;
    // }

    // public function setAgency(?DeliveryAgency $agency): static
    // {
    //     $this->agency = $agency;

    //     return $this;
    // }

    public function getAgency(): ?DeliveryAgency
    {
        return $this->agency;
    }

    public function setAgency(?DeliveryAgency $agency): static
    {
        $this->agency = $agency;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): static
    {
        $this->order = $order;

        return $this;
    }


}
