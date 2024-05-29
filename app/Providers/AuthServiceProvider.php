<?php

namespace App\Providers;

use App\Models\Ticket;
use App\V1\Policies\TicketPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @var array<class-string, class-string>
     */

    //  protected $policies = [
    //     Ticket::class => TicketPolicy::class,
    //     Ticket::class, => Ticket
    //  ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
