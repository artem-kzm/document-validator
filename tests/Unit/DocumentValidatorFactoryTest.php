<?php

use App\Document;
use App\Validation\DocumentValidatorFactory;
use App\Validation\RulesRegistryInterface;
use App\Validation\ValidationRuleInterface;
use App\Validation\Rules\ContentNotEmptyRule;
use App\Validation\Rules\ContentLengthRule;
use App\Validation\Rules\MetadataHasKeyRule;

function makeRegistry(array $tenantRules, array $fallback = []): RulesRegistryInterface
{
    return new class($tenantRules, $fallback) implements RulesRegistryInterface {
        public function __construct(
            private array $tenantRules,
            private array $fallback,
        ) {}

        public function getForTenant(string $tenantId): array
        {
            return $this->tenantRules[$tenantId] ?? $this->fallback;
        }
    };
}

function makeDocument(string $content = 'Hello world', array $metadata = []): Document
{
    return new Document(id: 1, tenantId: 'tenant_1', content: $content, metadata: $metadata);
}

it('applies tenant-specific rules', function () {
    $factory = new DocumentValidatorFactory(makeRegistry([
        'tenant_1' => [new ContentLengthRule(minLength: 100)],
    ]));

    $result = $factory->forTenant('tenant_1')->validate(makeDocument('Short'));

    expect($result->isValid)->toBeFalse()
        ->and($result->errors)->toContain('Content must be at least 100 characters long.');
});

it('falls back to default rules when tenant is not configured', function () {
    $factory = new DocumentValidatorFactory(makeRegistry(
        tenantRules: [],
        fallback: [new ContentNotEmptyRule()],
    ));

    $result = $factory->forTenant('unknown_tenant')->validate(makeDocument(''));

    expect($result->isValid)->toBeFalse()
        ->and($result->errors)->toContain('Content must not be empty.');
});

it('returns valid result when tenant rules pass', function () {
    $factory = new DocumentValidatorFactory(makeRegistry([
        'tenant_2' => [new MetadataHasKeyRule('department')],
    ]));

    $result = $factory->forTenant('tenant_2')->validate(makeDocument(metadata: ['department' => 'HR']));

    expect($result->isValid)->toBeTrue();
});

it('returns different validators per tenant', function () {
    $factory = new DocumentValidatorFactory(makeRegistry([
        'tenant_1' => [new ContentLengthRule(minLength: 100)],
        'tenant_2' => [new MetadataHasKeyRule('department')],
    ]));

    $document = makeDocument('Short', []);

    $result1 = $factory->forTenant('tenant_1')->validate($document);
    $result2 = $factory->forTenant('tenant_2')->validate($document);

    expect($result1->isValid)->toBeFalse()
        ->and($result2->isValid)->toBeFalse()
        ->and($result1->errors)->not->toEqual($result2->errors);
});
