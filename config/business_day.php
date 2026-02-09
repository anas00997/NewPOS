<?php

return [
    'start_date' => env('BUSINESS_DAY_START_DATE', date('Y-m-d')),
    'cutoff_time' => env('BUSINESS_DAY_CUTOFF_TIME', '20:00'), // 8 PM
];