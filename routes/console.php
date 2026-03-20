<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Jobs\FetchMetaLeadsJob;
 

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('leads:fetch-google')
    ->everyFiveMinutes();


Schedule::job(new FetchMetaLeadsJob())->hourly();