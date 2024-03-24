<?php

namespace App\Entity\User;

use App\Entity\BaseStatus;
use App\Repository\User\UserRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole extends BaseStatus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $metaCode = null;

    #[ORM\ManyToMany(targetEntity: Functionality::class, inversedBy: 'userRoles')]
    private Collection $functionalities;

    public function __construct()
    {
        $this->functionalities = new ArrayCollection();
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

    /**
     * @return Collection<int, Functionality>
     */
    public function getFunctionalities(): Collection
    {
        return $this->functionalities;
    }

    public function addFunctionality(Functionality $functionality): static
    {
        if (!$this->functionalities->contains($functionality)) {
            $this->functionalities->add($functionality);
        }

        return $this;
    }

    public function removeFunctionality(Functionality $functionality): static
    {
        $this->functionalities->removeElement($functionality);

        return $this;
    }
}
