<?php

namespace App\Providers;

use App\Events\QuizWasCreated;
use App\Listeners\PrepareForSendMailWithCorrectAnswers;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        QuizWasCreated::class => [
            PrepareForSendMailWithCorrectAnswers::class
        ]
    ];
}
