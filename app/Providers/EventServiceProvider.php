<?php

namespace App\Providers;

use App\Events\UserActionPerformed;
use App\Events\UserLoggedIn;
use App\Events\UserLoggedOut;
use App\Listeners\DeleteUnverifiedEmailNotification;
use App\Listeners\LogUserAction;
use App\Listeners\LogUserLoginLogout;
use App\Models\UserActionLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserLoggedIn::class => [
            LogUserLoginLogout::class . '@handleUserLoggedIn',
        ],
        UserLoggedOut::class => [
            LogUserLoginLogout::class . '@handleUserLoggedOut',
        ],
        UserActionPerformed::class => [
            LogUserAction::class,
        ],
        Verified::class => [
            DeleteUnverifiedEmailNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
