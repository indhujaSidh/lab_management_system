<?php

namespace App\Entity;

use App\Repository\ProcessStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProcessStatusRepository::class)]
class ProcessStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    private ?string $metaCode = null;

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
}
