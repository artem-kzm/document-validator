<?php

namespace App;

class Document
{
    public function __construct(
        private(set) int $id,
        private(set) string $tenantId,
        private(set) string $content,
        private(set) array $metadata,
    ) {}
}
