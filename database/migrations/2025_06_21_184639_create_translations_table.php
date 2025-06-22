<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('translation_groups');
            $table->string('key')->index(); // welcome.message
            $table->text('value');
            $table->string('language_code', 5);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('language_code')->references('code')->on('languages');
            $table->unique(['group_id', 'key', 'language_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
