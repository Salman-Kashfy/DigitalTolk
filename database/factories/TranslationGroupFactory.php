<?php

namespace Database\Factories;

use App\Modules\Translation\Entities\TranslationGroup; // Adjust namespace if your TranslationGroup model is elsewhere
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TranslationGroupFactory extends Factory
{
    protected $model = TranslationGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word() . '_' . Str::random(5),
        ];
    }
}
