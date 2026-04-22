<?php

use App\Document;
use App\Validation\Validators\BaseValidator;
use App\Validation\Rules\ContentNotEmptyRule;
use App\Validation\Rules\ContentLengthRule;
use App\Validation\Rules\MetadataHasKeyRule;

it('returns valid result when all rules pass', function () {
    $validator = new BaseValidator([
        new ContentNotEmptyRule(),
        new ContentLengthRule(minLength: 5),
    ]);

    $document = new Document(
        id: 1,
        tenantId: 'tenant_1',
        content: 'Hello world',
        metadata: [],
    );

    $result = $validator->validate($document);

    expect($result->isValid)->toBeTrue();
    expect($result->errors)->toBeEmpty();
});

it('returns invalid result with error messages when rules fail', function () {
    $validator = new BaseValidator([
        new ContentNotEmptyRule(),
        new ContentLengthRule(minLength: 100),
        new MetadataHasKeyRule('author'),
    ]);

    $document = new Document(
        id: 2,
        tenantId: 'tenant_1',
        content: 'Short',
        metadata: [],
    );

    $result = $validator->validate($document);

    expect($result->isValid)->toBeFalse();
    expect($result->errors)->toHaveCount(2);
    expect($result->errors)->toContain('Content must be at least 100 characters long.');
    expect($result->errors)->toContain("Metadata must contain the key 'author'.");
});

it('returns invalid result when content is empty', function () {
    $validator = new BaseValidator([
        new ContentNotEmptyRule(),
    ]);

    $document = new Document(id: 3, tenantId: 'tenant_1', content: '', metadata: []);

    $result = $validator->validate($document);

    expect($result->isValid)->toBeFalse();
    expect($result->errors[0])->toBe('Content must not be empty.');
});
