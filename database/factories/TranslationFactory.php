<?php

namespace Database\Factories;

use App\Modules\Translation\Entities\Translation;
use App\Modules\Translation\Entities\TranslationGroup;
use App\Modules\Translation\Entities\Language;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Modules\Translation\Entities\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Translation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // --- CHANGE THE 'key' GENERATION HERE ---
        // Option 1: Most reliable for guaranteed uniqueness - use UUID
        $key = $this->faker->unique()->uuid();

        // Option 2: Using unique slug with a random number (still better than just word)
        // $key = $this->faker->unique()->slug(3) . '_' . $this->faker->randomNumber(5);

        // Option 3: Using MD5 hash (also highly unique)
        // $key = $this->faker->unique()->md5();

        // --- END OF 'key' GENERATION CHANGE ---

        $groupId = TranslationGroup::inRandomOrder()->first()->id ?? null;
        $languageCode = Language::inRandomOrder()->first()->code ?? 'en';

        $value = $this->faker->sentence();

        return [
            'group_id' => $groupId,
            'key' => $key,
            'value' => $value,
            'language_code' => $languageCode,
        ];
    }

    /**
     * Indicate that the translation value should be a complex JSON string.
     * @return static
     */
    public function complexValue(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'value' => json_encode([
                    'singular' => $this->faker->sentence(3, true),
                    'plural' => $this->faker->sentences(2, true),
                    'context' => $this->faker->word(),
                ]),
            ];
        });
    }

    /**
     * Indicate a specific language for the translation.
     * @param string $code The language code.
     * @return static
     */
    public function forLanguage(string $code): static
    {
        return $this->state(fn (array $attributes) => [
            'language_code' => $code,
        ]);
    }

    /**
     * Indicate a specific group for the translation.
     * @param int $groupId The group ID.
     * @return static
     */
    public function forGroup(int $groupId): static
    {
        return $this->state(fn (array $attributes) => [
            'group_id' => $groupId,
        ]);
    }
}
