<?php

namespace App\Modules\Translation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'group_id',
        'key',
        'value',
        'language_code',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TranslationTag::class, 'translation_tag_pivot', 'translation_id', 'tag_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(TranslationGroup::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_code', 'code');
    }
}
