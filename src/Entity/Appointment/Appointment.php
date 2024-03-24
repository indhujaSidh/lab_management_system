<?php

namespace App\Entity\Appointment;

use App\Entity\BaseRecord;
use App\Entity\ProcessStatus;
use App\Entity\TimeSlot\TimeSlot;
use App\Entity\User\AppUser;
use App\Entity\User\Doctor;
use App\Repository\Appointment\AppointmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
class Appointment extends BaseRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $refNo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?AppUser $patientId = null;

    #[ORM\ManyToOne]
    private ?Doctor $doctorId = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $refDoctor = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProcessStatus $paymentStatus = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TimeSlot $timeSlot = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefNo(): ?string
    {
        return $this->refNo;
    }

    public function setRefNo(string $refNo): static
    {
        $this->refNo = $refNo;

        return $this;
    }

    public function getPatientId(): ?AppUser
    {
        return $this->patientId;
    }

    public function setPatientId(?AppUser $patientId): static
    {
        $this->patientId = $patientId;

        return $this;
    }

    public function getDoctorId(): ?Doctor
    {
        return $this->doctorId;
    }

    public function setDoctorId(?Doctor $doctorId): static
    {
        $this->doctorId = $doctorId;

        return $this;
    }

    public function getRefDoctor(): ?string
    {
        return $this->refDoctor;
    }

    public function setRefDoctor(?string $refDoctor): static
    {
        $this->refDoctor = $refDoctor;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentStatus(): ?ProcessStatus
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(?ProcessStatus $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getTimeSlot(): ?TimeSlot
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(?TimeSlot $timeSlot): static
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }
}
