<?php

namespace App\Service\Appointment;

interface IAppointmentMappingService
{
    public function getAppointmentTestResults($appointmentId):?array;

}