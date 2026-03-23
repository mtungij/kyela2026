<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Penalty is applied via Funga Hesabu (DailyReportController::closeAccount),
// so auto-scheduling is disabled to avoid duplicate/unexpected charges.

