<?php

namespace App\Form\Model;

class PasswordResetModal
{
    #[ORM\Column(type: 'string')]
    private string $oldPassword;

    #[ORM\Column(type: 'string')]
    private string $password;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }


}