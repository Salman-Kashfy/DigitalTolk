<?php

namespace App\Modules\Translation\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\Translation\Entities\Translation;

interface TranslationRepositoryInterface
{
    public function findById(int $id): ?Translation;
    public function findByKey(string $key, string $locale): ?Translation;
    public function search(
        ?string $query = null,
        ?array $tags = null,
        ?string $locale = null,
        ?string $group = null,
        int $perPage = 15
    ): LengthAwarePaginator;
    public function create(array $data): Translation;
    public function update(int $id, array $data): Translation;
    public function delete(int $id): bool;
    public function getTranslationsForExport(string $locale, ?array $groups = null, ?array $tags = null): array;
}
