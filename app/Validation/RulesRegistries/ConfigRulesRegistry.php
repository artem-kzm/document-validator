<?php

namespace App\Validation\RulesRegistries;

use App\Validation\RulesRegistryInterface;
use App\Validation\ValidationRuleInterface;

class ConfigRulesRegistry implements RulesRegistryInterface
{
    private array $tenants;
    private array $fallback;

    public function __construct()
    {
        $config = config('validation_rules');

        $this->tenants = $config['tenants'] ?? [];
        $this->fallback = $config['fallback'] ?? [];
    }

    public static function create(): static
    {
        return new static();
    }

    /** @return ValidationRuleInterface[] */
    public function getForTenant(string $tenantId): array
    {
        return $this->tenants[$tenantId] ?? $this->fallback;
    }
}
