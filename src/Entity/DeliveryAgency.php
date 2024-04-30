<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryAgency
 *
 * @ORM\Table(name="delivery_agency")
 * @ORM\Entity(repositoryClass=App\Repository\DeliveryAgencyRepository::class)
 */
class DeliveryAgency
{
    /**
     * @var int
     *
     * @ORM\Column(name="agency_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $agencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="agency_name", type="string", length=512, nullable=false)
     */
    private $agencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="agency_address", type="string", length=512, nullable=false)
     */
    private $agencyAddress;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nb_deliveries", type="integer", nullable=true)
     */
    private $nbDeliveries;

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

    public function __toString(): string
    {
        return $this->agencyName;
    }
}
