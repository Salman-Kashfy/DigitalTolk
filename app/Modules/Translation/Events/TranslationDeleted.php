<?php

namespace App\Modules\Translation\Events;

use App\Modules\Translation\Entities\Translation; // Assuming your Translation entity namespace
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TranslationDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Translation $translation;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
    }
}
