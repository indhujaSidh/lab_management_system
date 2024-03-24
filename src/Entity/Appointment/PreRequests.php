<?php

namespace App\Entity\Appointment;

use App\Entity\BaseRecord;
use App\Entity\ProcessStatus;
use App\Entity\User\AppUser;
use App\Entity\User\Doctor;
use App\Repository\Appointment\PreRequestsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PreRequestsRepository::class)]
class PreRequests extends BaseRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?AppUser $patient = null;

    #[ORM\Column(length: 20)]
    private ?string $contactNo = null;

    #[ORM\Column(length: 100)]
    private ?string $firstName = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $preferredDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $testsInfo = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prefferedTime = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ProcessStatus $processState = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string',nullable: true)]
    private string $testRequisitionFilename;

    #[ORM\ManyToOne]
    private ?Doctor $doctor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?AppUser
    {
        return $this->patient;
    }

    public function setPatient(?AppUser $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getContactNo(): ?string
    {
        return $this->contactNo;
    }

    public function setContactNo(string $contactNo): static
    {
        $this->contactNo = $contactNo;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
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

    public function getPreferredDate(): ?\DateTimeImmutable
    {
        return $this->preferredDate;
    }

    public function setPreferredDate(\DateTimeImmutable $preferredDate): static
    {
        $this->preferredDate = $preferredDate;

        return $this;
    }

    public function getTestsInfo(): ?string
    {
        return $this->testsInfo;
    }

    public function setTestsInfo(?string $testsInfo): static
    {
        $this->testsInfo = $testsInfo;

        return $this;
    }

    public function getPrefferedTime(): ?string
    {
        return $this->prefferedTime;
    }

    public function setPrefferedTime(?string $prefferedTime): static
    {
        $this->prefferedTime = $prefferedTime;

        return $this;
    }

    public function getProcessState(): ?ProcessStatus
    {
        return $this->processState;
    }

    public function setProcessState(?ProcessStatus $processState): static
    {
        $this->processState = $processState;

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

    public function getTestRequisitionFilename(): string
    {
        return $this->testRequisitionFilename;
    }

    public function setTestRequisitionFilename(string $testRequisitionFilename): self
    {
        $this->testRequisitionFilename = $testRequisitionFilename;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }


}
