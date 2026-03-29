<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('events:send-certificate-emails')->daily();
Schedule::command('events:send-reminders')->dailyAt('08:00');
Schedule::command('events:notify-pending-registration-payments')->dailyAt('10:00');
Schedule::job(new \Modules\Notifications\App\Jobs\DistributeScheduledNotificationsJob)->everyMinute();
Schedule::command('queue:prune-failed --hours=168')->weekly();
