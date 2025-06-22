<?php

namespace App\Modules\Translation\DTOs;

class CreateTranslationData
{
    public function __construct(
        public string $group,
        public string $key,
        public string $value,
        public string $locale,
        public ?array $tags = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            group: $data['group'] ?? null,
            key: $data['key'] ?? null,
            value: $data['value'] ?? null,
            locale: $data['locale'] ?? null,
            tags: $data['tags'] ?? null,
        );
    }
}
