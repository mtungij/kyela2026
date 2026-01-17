<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule penalty addition daily at midnight
Schedule::command('penalties:add')
    ->daily()
    ->at('00:01')
    ->timezone('Africa/Dar_es_Salaam');

