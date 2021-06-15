<?php declare(strict_types=1);

require_once 'Models/ValidationResult.php';

class ParametersValidator
{
    public function getInputParameters(): ?string
    {
        return $GLOBALS['argv'][1] ?? null;
    }

    public function validate($parameter): ValidationResult
    {
        if ($parameter === null) {
            return ValidationResult::success();
        }

        if (!is_numeric($parameter)) {
            return ValidationResult::fail('Worker ID must be a number.');
        }

        if ($parameter < 1) {
            return ValidationResult::fail('Worker ID must be a positive number greater than 0.');
        }

        return ValidationResult::success();
    }
}
