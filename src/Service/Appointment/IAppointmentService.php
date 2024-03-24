<?php

namespace App\Service\Appointment;

interface IAppointmentService
{
    public function getAppointmentListAction($patientId):?array;

}