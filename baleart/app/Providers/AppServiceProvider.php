<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Space;
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
        Route::bind('space', function ($value) { // En todos los ejemplos de la documentación de Laravel los bindings se hacen en este método por eso he decidido hacerlo aquí siguiendo la documentación ya que en routes/api.php no se hace, no funcionaría, lo he probado.
            return is_numeric($value)
                ? Space::findOrFail($value) // Cerca pel camp `id`
                : Space::where('regNumber', $value)->firstOrFail(); // Cerca pel camp `regNumber`: baleart.test/api/space/ECP827277 per exemple
        });
        Route::bind('user', function ($value) { // En todos los ejemplos de la documentación de Laravel los bindings se hacen en este método por eso he decidido hacerlo aquí siguiendo la documentación ya que en routes/api.php no se hace, no funcionaría, lo he probado.
            return is_numeric($value)
                ? User::findOrFail($value) // Cerca pel camp `id`
                : User::where('email', $value)->firstOrFail(); // Cerca pel camp `regNumber`: baleart.test/api/space/ECP827277 per exemple
        });
        
        RateLimiter::for('api', function ($request) {
            return Limit::perMinute(50); // Limita a 50 peticions per minut
        });
        Route::pattern('id', '[0-9]+'); // El paràmetre 'id' només pot ser numèric
    }
}
