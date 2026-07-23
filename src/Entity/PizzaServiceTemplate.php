<?php

namespace App\Entity;

use App\Repository\PizzaServiceTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PizzaServiceTemplateRepository::class)]
class PizzaServiceTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $startTime = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE)]
    private ?\DateTimeImmutable $endTime = null;

    #[ORM\Column]
    private ?int $slotDurationInMin = null;

    #[ORM\Column]
    private ?int $capacityPerSlot = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, PizzaService>
     */
    #[ORM\OneToMany(targetEntity: PizzaService::class, mappedBy: 'template')]
    private Collection $pizzaServices;

    public function __construct()
    {
        $this->pizzaServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, PizzaService>
     */
    public function getPizzaServices(): Collection
    {
        return $this->pizzaServices;
    }

    public function addPizzaService(PizzaService $pizzaService): static
    {
        if (!$this->pizzaServices->contains($pizzaService)) {
            $this->pizzaServices->add($pizzaService);
            $pizzaService->setTemplate($this);
        }

        return $this;
    }

    public function removePizzaService(PizzaService $pizzaService): static
    {
        if ($this->pizzaServices->removeElement($pizzaService)) {
            // set the owning side to null (unless already changed)
            if ($pizzaService->getTemplate() === $this) {
                $pizzaService->setTemplate(null);
            }
        }

        return $this;
    }
}
