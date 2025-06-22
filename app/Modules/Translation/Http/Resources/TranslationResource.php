<?php

namespace App\Modules\Translation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $value = $this->value;
        if (is_string($value) && json_decode($value) !== null) {
            // If value is stored as JSON string but intended to be returned as JSON object/array
            $value = json_decode($value, true);
        }

        return [
            'id' => $this->id,
            'key' => $this->key,
            'value' => $value,
            'locale' => $this->language_code,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
            'deleted_at' => $this->whenNotNull($this->deleted_at ? $this->deleted_at->toIso8601String() : null), // Show only if not null

            // Conditionally load related resources if they were eager loaded
            // This prevents N+1 query problems and only includes relations when requested (e.g., via '?with=group,tags')
            'group' => $this->whenLoaded('group', function () {
                return [
                    'id' => $this->group->id,
                    'name' => $this->group->name,
                ];
            }),
            'tags' => $this->whenLoaded('tags', function () {
                return $this->tags->pluck('name')->toArray();
            }),
            // If you need full tag objects:
            // 'tags' => TagResource::collection($this->whenLoaded('tags')),
        ];
    }
}
