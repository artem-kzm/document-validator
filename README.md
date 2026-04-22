# Document Validator

A service for validating documents using configurable, per-tenant validation rules.

## Requirements

- Docker & Docker Compose

## Run

```bash
# Build the Docker images
docker compose build

# Install dependencies with Composer
docker compose run --rm php composer install

# Run the entrypoint PHP script
docker compose run --rm php php index.php

# Run tests with Pest
docker compose run --rm php ./vendor/bin/pest
```

## Notes

### Validation Rules

Each rule is an independent class implementing `ValidationRuleInterface`:

```php
new ContentNotEmptyRule()
new ContentLengthRule(minLength: 100)
new MetadataHasKeyRule('author')
new ProhibitedWordsRule(['secret', 'password'])

// etc.
```

### Per-tenant Configuration

Rules are configured per tenant in `config/validation_rules.php`.
If a tenant has no rules, the `fallback` is used:

```php
return [
    'tenants' => [
        'tenant_1' => [
            new ContentLengthRule(minLength: 100),
            new MetadataHasKeyRule('author'),
        ],
    ],
    'fallback' => [
        new ContentNotEmptyRule(),
    ],
];
```

### Factory & Validator

To get a validator, use `DocumentValidatorFactory` with an injected `RulesRegistryInterface`.
By default, rules are loaded from a config file,
but the registry can be swapped for any other source: database, environment variables, or any custom implementation.
The main thing is that it returns rules for a given tenant ID:

```php
// Config-based (default)
$factory = new DocumentValidatorFactory(ConfigRulesRegistry::create());

// Any custom implementation
$factory = new DocumentValidatorFactory(new DatabaseRulesRegistry($db));
```

The factory creates a validator for the requested tenant.
In most cases `BaseValidator` is returned with the tenant's rules injected. 
For exceptional cases, the factory can return a fully tenant-specific validator - 
as long as it implements `DocumentValidatorInterface`:

```php
public function forTenant(string $tenantId): DocumentValidatorInterface
{
    // default path
    return new BaseValidator($this->registry->getForTenant($tenantId));

    // or a custom validator for a specific tenant if needed
    return new SpecialTenantValidator();
}
```
