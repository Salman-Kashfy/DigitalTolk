<?php

namespace App\Modules\Translation\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TranslationGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get the translations for the group.
     */
    public function translations()
    {
        return $this->hasMany(Translation::class, 'group_id');
    }
}
