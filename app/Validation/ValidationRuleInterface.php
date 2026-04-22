<?php

namespace App\Validation;

use App\Document;

interface ValidationRuleInterface
{
    public function validate(Document $document): bool;

    public function getMessage(): string;
}
