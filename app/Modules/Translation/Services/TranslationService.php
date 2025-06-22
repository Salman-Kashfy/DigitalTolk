<?php

namespace App\Modules\Translation\Services;

use App\Modules\Translation\DTOs\CreateTranslationData;
use App\Modules\Translation\DTOs\UpdateTranslationData;
use App\Modules\Translation\Entities\Translation;
use App\Modules\Translation\Contracts\TranslationRepositoryInterface;
use App\Modules\Translation\Entities\TranslationGroup;
use App\Modules\Translation\Entities\TranslationTag;
use App\Modules\Translation\Events\TranslationCreated;
use App\Modules\Translation\Events\TranslationDeleted;
use App\Modules\Translation\Events\TranslationUpdated;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcherInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class TranslationService
{
    public function __construct(
        private TranslationRepositoryInterface $repository,
        private EventDispatcherInterface $dispatcher
    ) {}

    public function createTranslation(CreateTranslationData $data): Translation
    {
        $translation = $this->repository->create([
            'group_id' => $this->resolveGroupId($data->group),
            'key' => $data->key,
            'value' => $data->value,
            'language_code' => $data->locale,
        ]);

        if ($data->tags) {
            $this->attachTags($translation, $data->tags);
        }

        $this->dispatcher->dispatch(new TranslationCreated($translation));

        return $translation;
    }

    public function findById(int $id): Translation
    {
        $translation = $this->repository->findById($id);
        if (!$translation) {
            throw new ModelNotFoundException("Translation with ID {$id} not found.");
        }
        return $translation;
    }

    public function getExportData(string $locale, ?array $groups = null, ?array $tags = null): array
    {
        return $this->repository->getTranslationsForExport($locale, $groups, $tags);
    }

    public function updateTranslation(int $id, UpdateTranslationData $data): Translation
    {

        $translation = $this->repository->findById($id);
        if (!$translation) {
            throw new ModelNotFoundException("Translation with ID {$id} not found.");
        }

        // Prepare data for update, resolving group ID if group name is provided
        $updateData = $data->toArray(); // Assuming DTO has a toArray method or similar
        if (isset($updateData['group'])) {
            $updateData['group_id'] = $this->resolveGroupId($updateData['group']);
            unset($updateData['group']); // Remove the string 'group' as repository expects 'group_id'
        }

        // Update the translation via the repository
        $updatedTranslation = $this->repository->update($id, $updateData);

        // Update tags if provided
        if (isset($data->tags)) { // Check if tags were part of the update request
            $this->syncTags($updatedTranslation, $data->tags);
        }

        $this->dispatcher->dispatch(new TranslationUpdated($updatedTranslation));

        return $updatedTranslation;
    }

    public function deleteTranslation(int $id): bool
    {
        $translation = $this->repository->findById($id);

        if (!$translation) {
            return false;
        }

        $result = $this->repository->delete($id);

        if ($result) {
            $this->dispatcher->dispatch(new TranslationDeleted($translation));
        }
        return $result;
    }

    public function searchTranslations(
        ?string $query = null,
        ?array $tags = null,
        ?string $locale = null,
        ?string $group = null,
        int $perPage = 15
    ): LengthAwarePaginator
    {
        return $this->repository->search($query, $tags, $locale, $group, $perPage);
    }

    private function resolveGroupId(string $groupName): int
    {
        $group = TranslationGroup::firstOrCreate(['name' => $groupName]);
        return $group->id;
    }

    private function attachTags(Translation $translation, array $tagNames): void
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tag = TranslationTag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        $translation->tags()->attach($tagIds);
    }

    private function syncTags(Translation $translation, array $tagNames): void
    {
        $tagIds = [];
        foreach ($tagNames as $tagName) {
            $tag = TranslationTag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        $translation->tags()->sync($tagIds);
    }

}
