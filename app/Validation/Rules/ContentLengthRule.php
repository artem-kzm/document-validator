<?php

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

readonly class ContentLengthRule implements ValidationRuleInterface
{
    public function __construct(
        private int  $minLength,
        private ?int $maxLength = null,
    ) {}

    public function validate(Document $document): bool
    {
        $length = mb_strlen($document->content);

        if ($length < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && $length > $this->maxLength) {
            return false;
        }

        return true;
    }

    public function getMessage(): string
    {
        if ($this->maxLength !== null) {
            return "Content length must be between {$this->minLength} and {$this->maxLength} characters.";
        }

        return "Content must be at least {$this->minLength} characters long.";
    }
}
