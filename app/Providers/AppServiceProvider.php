<?php

namespace App\Providers;

use App\Enums\Currencies;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Paginator::defaultView('layouts.pagination');
        View::share('defaultCurrency', Currencies::EUR->value);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'user' => User::class,
            'image' => Image::class
        ]);
    }
}
