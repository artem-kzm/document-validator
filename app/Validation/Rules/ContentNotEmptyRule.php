<?php

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

readonly class ContentNotEmptyRule implements ValidationRuleInterface
{
    public function validate(Document $document): bool
    {
        return trim($document->content) !== '';
    }

    public function getMessage(): string
    {
        return 'Content must not be empty.';
    }
}
