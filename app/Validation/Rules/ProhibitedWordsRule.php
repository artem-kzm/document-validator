<?php

namespace App\Validation\Rules;

use App\Document;
use App\Validation\ValidationRuleInterface;

readonly class ProhibitedWordsRule implements ValidationRuleInterface
{
    /** @param string[] $words */
    public function __construct(
        private array $words,
    ) {}

    public function validate(Document $document): bool
    {
        $content = mb_strtolower($document->content);

        return !array_find(
            $this->words,
            static fn ($word) => str_contains($content, mb_strtolower($word)));
    }

    public function getMessage(): string
    {
        return 'Content contains prohibited words: ' . implode(', ', $this->words) . '.';
    }
}
