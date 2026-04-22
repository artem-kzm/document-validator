<?php

namespace App\Validation;

use App\Document;

interface DocumentValidatorInterface
{
    public function validate(Document $document): ValidationResult;
}
