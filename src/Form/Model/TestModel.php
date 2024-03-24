<?php

namespace App\Form\Model;

use App\Entity\Test\Test;

class TestModel
{
    private ?Test $testName = null;

    public function getTestName(): ?Test
    {
        return $this->testName;
    }

    public function setTestName(?Test $testName): void
    {
        $this->testName = $testName;
    }

    function toArray()
    {
        return [
            'name' => $this->getTestName()
        ];
    }



}