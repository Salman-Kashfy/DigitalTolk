<?php

namespace App\Modules\Translation\Repositories;

use App\Modules\Translation\Contracts\TranslationRepositoryInterface;
use App\Modules\Translation\Entities\Translation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class TranslationRepository implements TranslationRepositoryInterface
{

    public function findById(int $id): ?Translation
    {
        return Translation::with(['group', 'tags'])->find($id);
    }

    public function findByKey(string $key, string $locale): ?Translation
    {
        return Translation::where('key', $key)
            ->where('language_code', $locale)
            ->first();
    }

    public function create(array $data): Translation
    {
        return Translation::create($data);
    }

    public function update(int $id, array $data): Translation
    {
        $translation = Translation::findOrFail($id);
        $translation->update($data);
        return $translation; // Return the updated model instance
    }

    public function delete(int $id): bool
    {
        // Using `destroy` will handle soft deletes automatically if the model uses SoftDeletes trait.
        // It returns the number of models deleted (1 for success, 0 for not found or failure).
        return Translation::destroy($id) > 0;
    }

    public function search(
        ?string $query = null,
        ?array $tags = null,
        ?string $locale = null,
        ?string $group = null,
        int $perPage = 15
    ): LengthAwarePaginator {
        $translations = Translation::query()
            ->when($query, function ($q, $queryText) {
                $q->where(function ($subQ) use ($queryText) {
                    $subQ->where('key', 'like', "%{$queryText}%")
                        ->orWhere('value', 'like', "%{$queryText}%");
                });
            })
            ->when($tags, function ($q, $tagNames) {
                $q->whereHas('tags', fn($r) => $r->whereIn('name', $tagNames));
            })
            ->when($locale, function ($q, $localeCode) {
                $q->where('language_code', $localeCode);
            })
            ->when($group, function ($q, $groupName) {
                $q->whereHas('group', fn($r) => $r->where('name', $groupName));
            })
            ->with(['group', 'tags']) // Eager load relationships for display
            ->paginate($perPage);

        return $translations;
    }

    public function getTranslationsForExport(string $locale, ?array $groups = null, ?array $tags = null): array
    {
        return Cache::remember(
            "translations.{$locale}." . md5(serialize([$groups, $tags])),
            now()->addHour(),
            function() use ($locale, $groups, $tags) {
                $query = Translation::where('language_code', $locale)
                    ->with(['group', 'tags'])
                    ->select(['group_id', 'key', 'value']);

                if ($groups) {
                    $query->whereHas('group', fn($q) => $q->whereIn('name', $groups));
                }

                if ($tags) {
                    $query->whereHas('tags', fn($q) => $q->whereIn('name', $tags));
                }

                return $query->get()
                    ->groupBy('group.name')
                    ->mapWithKeys(fn($items, $groupName) => [
                        $groupName => $items->pluck('value', 'key')
                    ])->toArray();
            }
        );
    }
}
