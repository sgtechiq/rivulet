<?php

$mailers = [];
$defaultMailer = env('MAIL_MAILER', 'smtp');
$mailers[$defaultMailer] = [
    'transport' => $defaultMailer,
    'host' => env('MAIL_HOST'),
    'port' => env('MAIL_PORT', 587),
    'username' => env('MAIL_USERNAME'),
    'password' => env('MAIL_PASSWORD'),
    'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS'),
        'name' => env('MAIL_FROM_NAME'),
    ],
];

// Support multiple mailers
for ($i = 2; env("MAIL_MAILER_{$i}_HOST"); $i++) {
    $mailerName = "mailer_$i";
    $mailers[$mailerName] = [
        'transport' => env("MAIL_MAILER_{$i}", 'smtp'),
        'host' => env("MAIL_MAILER_{$i}_HOST"),
        'port' => env("MAIL_MAILER_{$i}_PORT", 587),
        'username' => env("MAIL_MAILER_{$i}_USERNAME"),
        'password' => env("MAIL_MAILER_{$i}_PASSWORD"),
        'encryption' => env("MAIL_MAILER_{$i}_ENCRYPTION", 'tls'),
        'from' => [
            'address' => env("MAIL_MAILER_{$i}_FROM_ADDRESS"),
            'name' => env("MAIL_MAILER_{$i}_FROM_NAME"),
        ],
    ];
}

return [
    'default' => $defaultMailer,
    'mailers' => $mailers,
];