<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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
        Route::bind('post', function ($value) {
            return is_numeric($value)
                ? Post::findOrFail($value) // Cerca pel camp `id`
                : Post::where('title', 'like', '%' . $value . '%')->firstOrFail(); // Cerca pel camp `title`
        });
        Route::bind('user', function ($value) {
            return is_numeric($value)
                ? User::findOrFail($value) // Cerca per 'id'
                : User::where('email', $value)->firstOrFail(); // Cerca per 'email'
        });
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(10);
        });
        Route::pattern('id', '[0-9]+');
    }
}
