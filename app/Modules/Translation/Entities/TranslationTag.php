<?php

namespace App\Modules\Translation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TranslationTag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the translations that belong to this tag.
     */
    public function translations()
    {
        return $this->belongsToMany(Translation::class, 'translation_tag_pivot', 'tag_id', 'translation_id');
    }
}
