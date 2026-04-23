<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Add missing helper: remove specific query params from the current URL
        Request::macro('fullUrlWithoutParameters', function (array $keys): string {
            /** @var Request $this */
            $query = Arr::except($this->query(), $keys);

            return $query
                ? $this->url() . '?' . http_build_query($query)
                : $this->url();
        });
    }
}
