<?php

namespace App\Entity\User;

use App\Repository\User\FunctionalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FunctionalityRepository::class)]
class Functionality
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $metaCode = null;

    #[ORM\Column(length: 255)]
    private ?string $functionGroup = null;

    #[ORM\ManyToMany(targetEntity: UserRole::class, mappedBy: 'functionalities')]
    private Collection $userRoles;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
    }

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

    public function getFunctionGroup(): ?string
    {
        return $this->functionGroup;
    }

    public function setFunctionGroup(string $functionGroup): static
    {
        $this->functionGroup = $functionGroup;

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): static
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->addFunctionality($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): static
    {
        if ($this->userRoles->removeElement($userRole)) {
            $userRole->removeFunctionality($this);
        }

        return $this;
    }
}
