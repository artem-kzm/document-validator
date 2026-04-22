<?php

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

readonly class MetadataHasKeyRule implements ValidationRuleInterface
{
    public function __construct(
        private string $key,
    ) {}

    public function validate(Document $document): bool
    {
        return array_key_exists($this->key, $document->metadata);
    }

    public function getMessage(): string
    {
        return "Metadata must contain the key '{$this->key}'.";
    }
}
