<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\MailSendEvent' => [
        //     'App\Listeners\MailSendEventListener',
        // ],
        // 'App\Events\PasswordMailSendEvent' => [
        //     'App\Listeners\PasswordMailSendEventListener',
        // ],
        // 'App\Events\UserActionRecodeEvent' => [
        //     'App\Listeners\UserActionRecodeEventListener',
        // ],
        'App\Events\NotificationEvent' => [
            'App\Listeners\NotificationEventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
