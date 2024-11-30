<?php

namespace App\Providers;

use App\Http\Controllers\Api\V1\ApiController;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Support\Facades\Gate;
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
        Gate::guessPolicyNamesUsing(function (string $modelClass) {
            // this is not handel versioning !!
            $policies =  [
                Ticket::class => TicketPolicy::class
            ];

            return $policies[$modelClass];
        });
    }
}
