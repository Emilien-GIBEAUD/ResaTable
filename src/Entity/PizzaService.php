<?php

namespace App\Entity;

use App\Repository\PizzaServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaServiceRepository::class)]
class PizzaService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $serviceDate = null;

    #[ORM\ManyToOne(inversedBy: 'pizzaServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PizzaServiceTemplate $template = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column]
    private ?int $slotDurationInMin = null;

    #[ORM\Column]
    private ?int $capacityPerSlot = null;

    #[ORM\Column]
    private ?bool $bookingOpen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServiceDate(): ?\DateTimeImmutable
    {
        return $this->serviceDate;
    }

    public function setServiceDate(\DateTimeImmutable $serviceDate): static
    {
        $this->serviceDate = $serviceDate;

        return $this;
    }

    public function getTemplate(): ?PizzaServiceTemplate
    {
        return $this->template;
    }

    public function setTemplate(?PizzaServiceTemplate $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getStartTime(): ?\DateTimeImmutable
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeImmutable $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeImmutable
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeImmutable $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getSlotDurationInMin(): ?int
    {
        return $this->slotDurationInMin;
    }

    public function setSlotDurationInMin(int $slotDurationInMin): static
    {
        $this->slotDurationInMin = $slotDurationInMin;

        return $this;
    }

    public function getCapacityPerSlot(): ?int
    {
        return $this->capacityPerSlot;
    }

    public function setCapacityPerSlot(int $capacityPerSlot): static
    {
        $this->capacityPerSlot = $capacityPerSlot;

        return $this;
    }

    public function isBookingOpen(): ?bool
    {
        return $this->bookingOpen;
    }

    public function setBookingOpen(bool $bookingOpen): static
    {
        $this->bookingOpen = $bookingOpen;

        return $this;
    }
}
