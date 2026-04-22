<?php

namespace App\Validation;

readonly class ValidationResult
{
    /** @param string[] $errors */
    public function __construct(
        public bool $isValid,
        public array $errors = [],
    ) {}

    public static function valid(): self
    {
        return new self(isValid: true);
    }

    /** @param string[] $errors */
    public static function invalid(array $errors): self
    {
        return new self(isValid: false, errors: $errors);
    }
}

