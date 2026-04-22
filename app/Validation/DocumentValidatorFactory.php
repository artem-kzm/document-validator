<?php

namespace App\Validation;

use App\Validation\Validators\BaseValidator;

class DocumentValidatorFactory
{
    public function __construct(
        private readonly RulesRegistryInterface $registry,
    ) {}

    public function forTenant(string $tenantId): DocumentValidatorInterface
    {
        return new BaseValidator($this->registry->getForTenant($tenantId));
    }
}
