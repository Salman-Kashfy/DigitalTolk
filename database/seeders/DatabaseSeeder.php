<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call seeders for core data first (languages, tags, groups)
        $this->call([
            LanguageSeeder::class,
            TranslationTagSeeder::class,
            TranslationGroupSeeder::class,
        ]);

        // Call the TranslationSeeder for bulk data
        // Make sure it's called after its dependencies (groups, tags) are seeded
        $this->call([
            TranslationSeeder::class,
             UserSeeder::class,
        ]);
    }
}
