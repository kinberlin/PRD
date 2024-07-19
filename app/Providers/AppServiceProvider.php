<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Event;
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
        Carbon::setLocale(config('app.locale'));
        Schema::defaultStringLength(191);
        Event::listen(QueryExecuted::class, function ($query) {
            if (stripos($query->sql, 'update') !== false ||
                stripos($query->sql, 'insert') !== false ||
                stripos($query->sql, 'delete') !== false) {

                $sessionYear = session('currentYear');
                $currentYear = Carbon::now()->year;

                if ($sessionYear != $currentYear) {
                    throw new \Exception("Forbidden: The year in the session does not match the current year. You can't edit, add or delete a past year record.");
                }
            }
        });
    }
}
