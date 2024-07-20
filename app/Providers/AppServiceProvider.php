<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
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

            DB::listen(function ($query) {
                // Get the first 10 characters of the query and convert to lowercase
                $firstTenChars = strtolower(substr($query->sql, 0, 10));

                // Check if the query is an update, insert, or delete
                if (stripos($firstTenChars, 'update') !== false ||
                    stripos($firstTenChars, 'insert') !== false ||
                    stripos($firstTenChars, 'delete') !== false) {

                    // Get the session year and the current year
                    $sessionYear = session('current_year');
                    $currentYear = Carbon::now()->year;
                    dd($query->sql);
                    // If the session year does not match the current year, skip the query
                    if ($sessionYear != $currentYear) {
                        // Throw an exception to prevent execution (this won't stop the query but will help us handle it)
                        throw new \Exception('Query skipped due to session year mismatch.');
                    }
                }
            });
        });
    }
}
