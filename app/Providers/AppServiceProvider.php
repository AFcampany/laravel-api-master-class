<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Models\User;
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
                Ticket::class => \App\Policies\V1\TicketPolicy::class,
                User::class => \App\Policies\V1\UserPolicy::class,
            ];

            return $policies[$modelClass];
        });
    }
}
