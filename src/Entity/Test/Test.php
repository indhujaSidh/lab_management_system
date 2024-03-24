<?php

namespace App\Entity\Test;

use App\Entity\BaseRecord;
use App\Repository\Test\TestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: TestRepository::class)]
class Test extends BaseRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $metaCode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TestCategory $category = null;

    #[ORM\Column]
    private ?float $processingPeriod = null;


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

    public function getMetaCode(): ?string
    {
        return $this->metaCode;
    }

    public function setMetaCode(string $metaCode): static
    {
        $this->metaCode = $metaCode;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?TestCategory
    {
        return $this->category;
    }

    public function setCategory(?TestCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getProcessingPeriod(): ?float
    {
        return $this->processingPeriod;
    }

    public function setProcessingPeriod(float $processingPeriod): static
    {
        $this->processingPeriod = $processingPeriod;

        return $this;
    }
    
}
