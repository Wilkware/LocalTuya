<?php

declare(strict_types=1);

class ValidationTest extends TestCaseSymconValidation
{
    public function testValidateLibrary(): void
    {
        $this->validateLibrary(__DIR__ . '/..');
    }

    public function testValidateCeilingFan(): void
    {
        $this->validateModule(__DIR__ . '/../CeilingFan');
    }

    public function testValidateVacuumCleaner(): void
    {
        $this->validateModule(__DIR__ . '/../VacuumCleaner');
    }
}