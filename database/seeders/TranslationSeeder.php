<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Translation\Entities\Translation;
use App\Modules\Translation\Entities\TranslationGroup;
use App\Modules\Translation\Entities\TranslationTag;
use App\Modules\Translation\Entities\Language;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure groups, tags, and languages are seeded first
        if (TranslationGroup::count() === 0) {
            $this->call(TranslationGroupSeeder::class);
        }
        if (TranslationTag::count() === 0) {
            $this->call(TranslationTagSeeder::class);
        }
        if (Language::count() === 0) {
            $this->call(LanguageSeeder::class);
        }

        $allGroupIds = TranslationGroup::pluck('id')->toArray();
        $allTagIds = TranslationTag::pluck('id')->toArray();
        $activeLanguageCodes = Language::where('is_active', true)->pluck('code')->toArray();

        if (empty($allGroupIds) || empty($allTagIds) || empty($activeLanguageCodes)) {
            $this->command->error('Not enough data in groups, tags, or languages tables to seed translations. Please run previous seeders.');
            return;
        }

        $targetTranslations = 100000;
        $bar = $this->command->getOutput()->createProgressBar($targetTranslations);
        $bar->start();

        $chunkSize = 5000; // Increased chunk size for better performance with mass inserts
        for ($i = 0; $i < $targetTranslations / $chunkSize; $i++) {
            $translationsToInsert = [];
            for ($j = 0; $j < $chunkSize; $j++) {
                $translationsToInsert[] = [
                    'group_id' => fake()->randomElement($allGroupIds),
                    'key' => Str::uuid(), // <--- CHANGE THIS LINE! Use UUID for robust uniqueness
                    // OR: 'key' => Str::random(32), // Another strong option
                    'value' => fake()->sentence(),
                    'language_code' => fake()->randomElement($activeLanguageCodes),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('translations')->insert($translationsToInsert);

            $bar->advance($chunkSize);
        }

        $bar->finish(); // Finish the first progress bar
        $this->command->info("\nGenerating pivot data for tags...");

        $pivotAttachments = [];
        // Important: If you just inserted 100k records, loading all IDs might be memory intensive.
        // For production, you might rethink this approach or chunk it.
        // For now, assuming it fits memory.
        $allTranslationIds = Translation::pluck('id')->toArray();

        $pivotBar = $this->command->getOutput()->createProgressBar(count($allTranslationIds));
        $pivotBar->start();

        foreach ($allTranslationIds as $translationId) {
            $numberOfTags = rand(1, min(3, count($allTagIds))); // Attach 1 to 3 tags
            // array_rand returns keys, so array_flip is important if you need values
            $randomTagIds = (array) array_rand(array_flip($allTagIds), $numberOfTags);

            foreach ($randomTagIds as $tagId) {
                $pivotAttachments[] = [
                    'translation_id' => $translationId,
                    'tag_id' => $tagId,
                ];
            }
            $pivotBar->advance();
        }
        $pivotBar->finish();
        $this->command->info("\nInserting pivot data in batches...");

        // Batch insert into the pivot table
        $pivotChunkSize = 5000;
        foreach (array_chunk($pivotAttachments, $pivotChunkSize) as $chunk) {
            DB::table('translation_tag_pivot')->insert($chunk);
        }

        $this->command->info("\nTranslations seeded successfully with tags.");
    }
}
