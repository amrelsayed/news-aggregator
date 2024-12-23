<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('news:fetch newsapi')->hourly();
Schedule::command('news:fetch nytimes')->hourly();
Schedule::command('news:fetch guardian')->hourly();
