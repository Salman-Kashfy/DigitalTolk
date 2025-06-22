<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Translation\Entities\Language; // Adjust namespace if needed

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = [
            ['code' => 'en', 'name' => 'English', 'is_active' => true],
            ['code' => 'fr', 'name' => 'French', 'is_active' => true],
            ['code' => 'es', 'name' => 'Spanish', 'is_active' => true],
            ['code' => 'de', 'name' => 'German', 'is_active' => true],
            ['code' => 'ar', 'name' => 'Arabic', 'is_active' => true],
            ['code' => 'ur', 'name' => 'Urdu', 'is_active' => false], // Example of an inactive language
        ];

        foreach ($languages as $language) {
            Language::firstOrCreate(['code' => $language['code']], $language);
        }
    }
}
