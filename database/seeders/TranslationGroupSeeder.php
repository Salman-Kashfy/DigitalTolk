<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Translation\Entities\TranslationGroup; // Adjust namespace if needed

class TranslationGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $predefinedGroups = ['auth', 'validation', 'ui', 'emails', 'messages', 'buttons', 'headers', 'errors'];

        foreach ($predefinedGroups as $groupName) {
            TranslationGroup::firstOrCreate(['name' => $groupName]);
        }

        // Create an additional 92 random groups to reach a total of 100 groups,
        // as hinted in your initial TranslationSeeder snippet.
        TranslationGroup::factory()->count(92)->create();

        $this->command->info('Translation groups seeded.');
    }
}
