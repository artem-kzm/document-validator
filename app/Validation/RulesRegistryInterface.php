<?php

namespace App\Validation;

interface RulesRegistryInterface
{
    /** @return ValidationRuleInterface[] */
    public function getForTenant(string $tenantId): array;
}
