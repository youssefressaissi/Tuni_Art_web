<?php

namespace App\Entity;

use App\Repository\DeliveryAgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryAgencyRepository::class)]
class DeliveryAgency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $agencyId = null;

    #[ORM\Column(length: 512)]
    private ?string $agencyName = null;

    #[ORM\Column(length: 512)]
    private ?string $agencyAddress = null;

    #[ORM\Column]
    private ?int $nbDeliveries;

    #[ORM\OneToMany(mappedBy: 'agency', targetEntity: Delivery::class)]
    private Collection $deliveries;

    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
    }

    public function getAgencyId(): ?int
    {
        return $this->agencyId;
    }

    public function getAgencyName(): ?string
    {
        return $this->agencyName;
    }

    public function setAgencyName(string $agencyName): static
    {
        $this->agencyName = $agencyName;

        return $this;
    }

    public function getAgencyAddress(): ?string
    {
        return $this->agencyAddress;
    }

    public function setAgencyAddress(string $agencyAddress): static
    {
        $this->agencyAddress = $agencyAddress;

        return $this;
    }

    public function getNbDeliveries(): ?int
    {
        return $this->nbDeliveries;
    }

    public function setNbDeliveries(?int $nbDeliveries): static
    {
        $this->nbDeliveries = $nbDeliveries;

        return $this;
    }

    /**
     * @return Collection<int, Delivery>
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): static
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries->add($delivery);
            $delivery->setAgency($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): static
    {
        if ($this->deliveries->removeElement($delivery)) {
            // set the owning side to null (unless already changed)
            if ($delivery->getAgency() === $this) {
                $delivery->setAgency(null);
            }
        }

        return $this;
    }


}
