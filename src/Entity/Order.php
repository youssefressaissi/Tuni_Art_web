<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: "`order`")] // Specify a custom table name
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $orderId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $orderDate = null;

    #[ORM\Column]
	private ?float $totalprice = null;

    #[ORM\Column]
    private bool $status = false;    

    #[ORM\Column(name: "uid", type: "integer", nullable: true)]
    private ?int $uid = null;


    //#[ORM\ManyToOne(inversedBy: 'orders')]
   // #[ORM\JoinColumn(nullable: false)]
    //private ?User $user = null;

    // #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    // private ?User $uid;

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(?int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): static
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    public function getTotalprice(): ?float
    {
        return $this->totalprice;
    }

    public function setTotalprice(float $totalprice): static
    {
        $this->totalprice = $totalprice;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    // public function getUid(): ?User
    // {
    //     return $this->uid;
    // }

    // public function setUid(?User $uid): static
    // {
    //     $this->uid = $uid;

    //     return $this;
    // }

    // public function getUser(): ?User
    // {
    //     return $this->user;
    // }

    // public function setUser(?User $user): static
    // {
    //     $this->user = $user;

    //     return $this;
    // }

 
}
