<?php

namespace App\Providers;

use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @var array<class-string, class-string>
     */

     protected $policies = [
        Ticket::class => TicketPolicy::class,
     ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
