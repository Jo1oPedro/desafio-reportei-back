<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public function since($since_days, $format = "Y-m-d\TH:i:s\Z"): string
    {
        $now = Carbon::now();
        $since = $now->subDays(90);
        return $since->format($format);
    }
}
