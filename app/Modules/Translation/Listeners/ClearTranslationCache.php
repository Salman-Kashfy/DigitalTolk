<?php

namespace App\Modules\Translation\Listeners;

use App\Modules\Translation\Entities\Language;
use App\Modules\Translation\Events\TranslationCreated;
use Illuminate\Support\Facades\Cache;

class ClearTranslationCache
{
    public function handle(TranslationCreated $event)
    {
        $locales = Language::active()->pluck('code');

        foreach ($locales as $locale) {
            Cache::tags(["translations.{$locale}"])->flush();
        }
    }
}
