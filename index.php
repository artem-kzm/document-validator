<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Document;
use App\Validation\DocumentValidatorFactory;
use App\Validation\RulesRegistries\ConfigRulesRegistry;

$validatorFactory = new DocumentValidatorFactory(
    ConfigRulesRegistry::create()
);

$documents = [
    new Document(id: 1, tenantId: 'tenant_1', content: 'Short', metadata: ['type' => 'pdf']),
    new Document(id: 2, tenantId: 'tenant_1', content: 'password: 123', metadata: ['author' => 'pdf']),
    new Document(id: 3, tenantId: 'tenant_2', content: 'Hello world', metadata: ['department' => 'HR']),
    new Document(id: 4, tenantId: 'tenant_2', content: 'This is a valid document content.', metadata: []),
    new Document(id: 5, tenantId: 'tenant_3', content: '', metadata: []),
    new Document(id: 6, tenantId: 'tenant_3', content: 'Financial report for Q2', metadata: []),
];

foreach ($documents as $document) {
    echo "--- Document #{$document->id} [{$document->tenantId}] ---" . PHP_EOL;

    $validator = $validatorFactory->forTenant($document->tenantId);
    $result = $validator->validate($document);

    if ($result->isValid) {
        echo "✅ Document is valid." . PHP_EOL;
    }

    foreach ($result->errors as $error) {
        echo "❌ $error" . PHP_EOL;
    }

    echo PHP_EOL;
}
