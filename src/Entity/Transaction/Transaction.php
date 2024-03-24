<?php
namespace App\Entity\Transaction;


use App\Entity\Appointment\Appointment;
use App\Repository\Transaction\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Appointment $appointmentId = null;

    #[ORM\Column(nullable: true)]
    private ?int $amount = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $paidAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $referenceNo = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $identification = null;

    #[ORM\Column(nullable: true, options: ["default" => false])]
    private ?bool $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $statusCode = null;

    #[ORM\Column(nullable: true)]
    private ?int $returnType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notifyParameters = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->status = false;
    }

    function __toString()
    {
        return $this->getId() == null ? 'New Update' : $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function setPaidAt(?\DateTimeImmutable $paidAt): static
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    public function getReferenceNo(): ?string
    {
        return $this->referenceNo;
    }

    public function setReferenceNo(?string $referenceNo): static
    {
        $this->referenceNo = $referenceNo;

        return $this;
    }

    public function getIdentification(): ?string
    {
        return $this->identification;
    }

    public function setIdentification(?string $identification): static
    {
        $this->identification = $identification;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setStatusCode(?int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getReturnType(): ?int
    {
        return $this->returnType;
    }

    public function setReturnType(?int $returnType): static
    {
        $this->returnType = $returnType;

        return $this;
    }

    public function getNotifyParameters(): ?string
    {
        return $this->notifyParameters;
    }

    public function setNotifyParameters(?string $notifyParameters): static
    {
        $this->notifyParameters = $notifyParameters;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getAppointmentId(): ?Appointment
    {
        return $this->appointmentId;
    }

    public function setAppointmentId(?Appointment $appointmentId): static
    {
        $this->appointmentId = $appointmentId;

        return $this;
    }
}
