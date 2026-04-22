<?php

declare(strict_types=1);

use App\Validation\Rules\ContentLengthRule;
use App\Validation\Rules\ContentNotEmptyRule;
use App\Validation\Rules\MetadataHasKeyRule;
use App\Validation\Rules\ProhibitedWordsRule;

return [
    'tenants' => [
        'tenant_1' => [
            new ContentLengthRule(minLength: 100),
            new MetadataHasKeyRule('author'),
            new ProhibitedWordsRule(['password', 'secret', 'PIN']),
        ],

        'tenant_2' => [
            new MetadataHasKeyRule('department'),
        ],
    ],

    'fallback' => [
        new ContentNotEmptyRule(),
    ],
];
