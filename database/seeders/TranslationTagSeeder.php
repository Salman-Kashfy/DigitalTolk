<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Translation\Entities\TranslationTag; // Adjust namespace if needed

class TranslationTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['web', 'mobile', 'desktop'];

        foreach ($tags as $tagName) {
            TranslationTag::firstOrCreate(['name' => $tagName]);
        }
    }
}
