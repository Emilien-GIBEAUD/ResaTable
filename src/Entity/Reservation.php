<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $accessToken = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $confirmationExpiresAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, ReservationItem>
     */
    #[ORM\OneToMany(targetEntity: ReservationItem::class, mappedBy: 'reservation', orphanRemoval: true)]
    private Collection $reservationItems;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PizzaServiceSlot $slot = null;

    public function __construct()
    {
        $this->reservationItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): static
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getConfirmationExpiresAt(): ?\DateTimeImmutable
    {
        return $this->confirmationExpiresAt;
    }

    public function setConfirmationExpiresAt(?\DateTimeImmutable $confirmationExpiresAt): static
    {
        $this->confirmationExpiresAt = $confirmationExpiresAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ReservationItem>
     */
    public function getReservationItems(): Collection
    {
        return $this->reservationItems;
    }

    public function addReservationItem(ReservationItem $reservationItem): static
    {
        if (!$this->reservationItems->contains($reservationItem)) {
            $this->reservationItems->add($reservationItem);
            $reservationItem->setReservation($this);
        }

        return $this;
    }

    public function removeReservationItem(ReservationItem $reservationItem): static
    {
        if ($this->reservationItems->removeElement($reservationItem)) {
            // set the owning side to null (unless already changed)
            if ($reservationItem->getReservation() === $this) {
                $reservationItem->setReservation(null);
            }
        }

        return $this;
    }

    public function getSlot(): ?PizzaServiceSlot
    {
        return $this->slot;
    }

    public function setSlot(?PizzaServiceSlot $slot): static
    {
        $this->slot = $slot;

        return $this;
    }
}
