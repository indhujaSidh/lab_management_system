<?php

namespace App\Form\Model;

use App\Entity\Test\Test;
use App\Entity\TimeSlot\TimeSlot;
use App\Entity\User\AppUser;
use App\Entity\User\Doctor;

class AppointmentModel
{
    private ?AppUser $patient = null;

    private ?Doctor $doctor = null;

    private ?string $paymentStatus = null;

    private ?string $refDoctor = null;

    private ?TimeSlot $timeSlot = null;

    private ?array $tests = null;

    private ?\DateTimeImmutable $sampleCollected = null;

    public function getPatient(): ?AppUser
    {
        return $this->patient;
    }

    public function setPatient(?AppUser $patient): void
    {
        $this->patient = $patient;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): void
    {
        $this->doctor = $doctor;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(?string $paymentStatus): void
    {
        $this->paymentStatus = $paymentStatus;
    }

    public function getRefDoctor(): ?string
    {
        return $this->refDoctor;
    }

    public function setRefDoctor(?string $refDoctor): void
    {
        $this->refDoctor = $refDoctor;
    }

    public function getTimeSlot(): ?TimeSlot
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(?TimeSlot $timeSlot): void
    {
        $this->timeSlot = $timeSlot;
    }

    public function getTests(): ?array
    {
        return $this->tests;
    }

    public function setTests(?array $tests): void
    {
        $this->tests = $tests;
    }

    public function getSampleCollected(): ?\DateTimeImmutable
    {
        return $this->sampleCollected;
    }

    public function setSampleCollected(?\DateTimeImmutable $sampleCollected): void
    {
        $this->sampleCollected = $sampleCollected;
    }

    function toArray()
    {
        return [
            'patient' => $this->getPatient(),
            'doctor' => $this->getDoctor(),
            'paymentStatus' => $this->getPaymentStatus(),
            'refDoctor' => $this->getRefDoctor(),
            'timeSlot' => $this->getTimeSlot(),
            'tests' => $this->getTests(),
            'sampleCollected' => $this->getSampleCollected(),
        ];
    }

}