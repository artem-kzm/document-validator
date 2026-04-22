<?php

namespace app\Validation\Validators;

use App\Document;
use App\Validation\DocumentValidatorInterface;
use App\Validation\ValidationResult;
use App\Validation\ValidationRuleInterface;

class BaseValidator implements DocumentValidatorInterface
{
    /** @param ValidationRuleInterface[] $rules */
    public function __construct(
        private readonly array $rules,
    ) {}

    public function validate(Document $document): ValidationResult
    {
        $errors = [];

        foreach ($this->rules as $rule) {
            if (!$rule->validate($document)) {
                $errors[] = $rule->getMessage();
            }
        }

        return empty($errors)
            ? ValidationResult::valid()
            : ValidationResult::invalid($errors);
    }
}
