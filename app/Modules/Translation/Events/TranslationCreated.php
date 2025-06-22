<?php

namespace App\Modules\Translation\Events;

use App\Modules\Translation\Entities\Translation;

class TranslationCreated
{
    public function __construct(
        public Translation $translation
    ) {}
}
