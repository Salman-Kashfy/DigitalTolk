<?php

namespace App\Modules\Translation\DTOs;

class UpdateTranslationData
{

    public function __construct(
        public ?string $group = null,
        public ?string $key = null,
        public ?string $value = null,
        public ?string $locale = null,
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

    public function toArray(): array
    {
        $data = [];

        if ($this->group !== null) {
            $data['group'] = $this->group; // Service will resolve group_id
        }
        if ($this->key !== null) {
            $data['key'] = $this->key;
        }
        if ($this->value !== null) {
            $data['value'] = $this->value;
        }
        if ($this->locale !== null) {
            $data['language_code'] = $this->locale; // Match database column name
        }
        return $data;
    }
}
