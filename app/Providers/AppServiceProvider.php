<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Spatie\LaravelTypeScriptTransformer\TypeScriptTransformerApplicationServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // The TypeScript type generator is a dev-only tool: its package is not
        // installed in production, so only register it when it is available.
        if (class_exists(TypeScriptTransformerApplicationServiceProvider::class)) {
            $this->app->register(TypeScriptTransformerServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
