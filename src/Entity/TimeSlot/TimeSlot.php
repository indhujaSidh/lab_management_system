<?php

namespace App\Entity\TimeSlot;

use App\Repository\TimeSlot\TimeSlotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TimeSlotRepository::class)]
class TimeSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimeRange $timeRange = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $allocatedPatients = null;

    #[ORM\Column]
    private ?int $availableSlots = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTimeRange(): ?TimeRange
    {
        return $this->timeRange;
    }

    public function setTimeRange(?TimeRange $timeRange): static
    {
        $this->timeRange = $timeRange;

        return $this;
    }

    public function getAllocatedPatients(): ?int
    {
        return $this->allocatedPatients;
    }

    public function setAllocatedPatients(int $allocatedPatients): static
    {
        $this->allocatedPatients = $allocatedPatients;

        return $this;
    }

    public function getAvailableSlots(): ?int
    {
        return $this->availableSlots;
    }

    public function setAvailableSlots(int $availableSlots): static
    {
        $this->availableSlots = $availableSlots;

        return $this;
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
}
