<?php

namespace App\Modules\Translation\Events;

use App\Modules\Translation\Entities\Translation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranslationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Translation $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }
}
