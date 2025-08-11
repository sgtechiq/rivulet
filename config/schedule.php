<?php

/**
 * Scheduled Jobs Configuration
 *
 * Defines recurring jobs that run at specified intervals.
 *
 * Format:
 *   'schedule_name' => [
 *       'job'  => 'Fully\Qualified\JobClass',
 *       'data' => ['key' => 'value'] // Optional payload
 *   ],
 */
return [
    // Example configurations (uncomment to use):

    // Run every minute
    // 'everyMinute' => [
    //     'job'  => 'App\Jobs\ExampleJob',
    //     'data' => ['key' => 'value']
    // ],

    // Run hourly at :15 minutes
    // 'hourlyAt15' => [
    //     'job'  => 'App\Jobs\HourlyReport',
    //     'data' => ['report_type' => 'summary']
    // ],

    // Run daily at midnight
    // 'daily' => [
    //     'job' => 'App\Jobs\DailyCleanup'
    // ]
];
