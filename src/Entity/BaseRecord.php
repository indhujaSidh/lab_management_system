<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exception;

#[ORM\MappedSuperclass]
class BaseRecord
{
    const TIMEZONE = 'Asia/Colombo';

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?bool $isDeleted = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->isDeleted = false;
        $this->updatedAt = new \DateTimeImmutable('now', new \DateTimeZone(self::TIMEZONE));
        $this->createdAt = new \DateTimeImmutable('now', new \DateTimeZone(self::TIMEZONE));
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): static
    {
        $this->isDeleted = $isDeleted;

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

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
