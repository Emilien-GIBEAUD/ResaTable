<?php

namespace App\Entity;

use App\Repository\PizzaServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, PizzaServiceSlot>
     */
    #[ORM\OneToMany(targetEntity: PizzaServiceSlot::class, mappedBy: 'service', orphanRemoval: true)]
    private Collection $pizzaServiceSlots;

    public function __construct()
    {
        $this->pizzaServiceSlots = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, PizzaServiceSlot>
     */
    public function getPizzaServiceSlots(): Collection
    {
        return $this->pizzaServiceSlots;
    }

    public function addPizzaServiceSlot(PizzaServiceSlot $pizzaServiceSlot): static
    {
        if (!$this->pizzaServiceSlots->contains($pizzaServiceSlot)) {
            $this->pizzaServiceSlots->add($pizzaServiceSlot);
            $pizzaServiceSlot->setService($this);
        }

        return $this;
    }

    public function removePizzaServiceSlot(PizzaServiceSlot $pizzaServiceSlot): static
    {
        if ($this->pizzaServiceSlots->removeElement($pizzaServiceSlot)) {
            // set the owning side to null (unless already changed)
            if ($pizzaServiceSlot->getService() === $this) {
                $pizzaServiceSlot->setService(null);
            }
        }

        return $this;
    }
}
