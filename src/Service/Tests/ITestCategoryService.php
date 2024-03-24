<?php

namespace App\Service\Tests;

use Symfony\Component\HttpFoundation\Request;

interface ITestCategoryService
{
    public function saveTestCategoryFormData(Request $request): array;

    public function getTestCategoryFormData(Request $request): array;

}