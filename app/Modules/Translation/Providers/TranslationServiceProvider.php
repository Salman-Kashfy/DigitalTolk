<?php

namespace App\Modules\Translation\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Translation\Contracts\TranslationRepositoryInterface;
use App\Modules\Translation\Repositories\TranslationRepository;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TranslationRepositoryInterface::class,
            TranslationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            // If the model name starts with 'App\Modules\Translation\Entities\'
            if (Str::startsWith($modelName, 'App\\Modules\\Translation\\Entities\\')) {
                // Extract the base class name (e.g., 'TranslationGroup')
                $modelBaseName = class_basename($modelName);
                // Construct the correct factory namespace and class name
                return 'Database\\Factories\\' . $modelBaseName . 'Factory';
            }

            // Fallback to Laravel's default guessing for other models (e.g., App\Models\User)
            return 'Database\\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }
}
