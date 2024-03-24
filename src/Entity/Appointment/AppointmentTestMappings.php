<?php

namespace App\Entity\Appointment;

use App\Entity\Test\Test;
use App\Entity\User\Technician;
use App\Repository\Appointment\AppointmentTestMappingsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentTestMappingsRepository::class)]
class AppointmentTestMappings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appointment $AppointmentId = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Test $test = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $sampleCollected = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $readyDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $printedDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comments = null;
    #[ORM\Column(type: 'string',nullable: true)]
    private string $reportFile;

    #[ORM\ManyToOne]
    private ?Technician $technician = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointmentId(): ?Appointment
    {
        return $this->AppointmentId;
    }

    public function setAppointmentId(?Appointment $AppointmentId): static
    {
        $this->AppointmentId = $AppointmentId;

        return $this;
    }

    public function getTest(): ?Test
    {
        return $this->test;
    }

    public function setTest(?Test $test): static
    {
        $this->test = $test;

        return $this;
    }

    public function getSampleCollected(): ?\DateTimeImmutable
    {
        return $this->sampleCollected;
    }

    public function setSampleCollected(\DateTimeImmutable $sampleCollected): static
    {
        $this->sampleCollected = $sampleCollected;

        return $this;
    }

    public function getReadyDate(): ?\DateTimeImmutable
    {
        return $this->readyDate;
    }

    public function setReadyDate(\DateTimeImmutable $readyDate): static
    {
        $this->readyDate = $readyDate;

        return $this;
    }

    public function getPrintedDate(): ?\DateTimeImmutable
    {
        return $this->printedDate;
    }

    public function setPrintedDate(?\DateTimeImmutable $printedDate): static
    {
        $this->printedDate = $printedDate;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): static
    {
        $this->comments = $comments;

        return $this;
    }

    public function geReportFile(): string
    {
        return $this->reportFile;
    }

    public function setReportFile(string $reportFile): self
    {
        $this->reportFile = $reportFile;

        return $this;
    }
    function toArray()
    {
        return [
            'name' => $this->getTest(),
            'technician' => $this->getTechnician(),
            'test' => $this->getTest(),
            'appointment' => $this->getAppointmentId(),
        ];
    }

    public function getTechnician(): ?Technician
    {
        return $this->technician;
    }

    public function setTechnician(?Technician $technician): static
    {
        $this->technician = $technician;

        return $this;
    }
}
