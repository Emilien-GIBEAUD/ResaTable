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
}
