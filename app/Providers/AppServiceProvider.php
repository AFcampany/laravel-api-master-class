<?php

namespace App\Providers;

use App\Exceptions\Api\ApiExceptionHandler;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Handler::class, ApiExceptionHandler::class);
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
