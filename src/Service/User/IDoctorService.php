<?php

namespace App\Service\User;

interface IDoctorService
{
    public function getDoctorsForList(): ?array;

}