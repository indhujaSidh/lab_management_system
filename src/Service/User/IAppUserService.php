<?php

namespace App\Service\User;

interface IAppUserService
{
    public function getBackendUserListAction();

    public function getUsersContactNumbers($para):?array;

}