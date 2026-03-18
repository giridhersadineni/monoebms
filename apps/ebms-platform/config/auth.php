<?php

return [

    'defaults' => [
        'guard' => 'student',
        'passwords' => 'students',
    ],

    'guards' => [
        'student' => [
            'driver'   => 'session',
            'provider' => 'students',
        ],
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admin_users',
        ],
    ],

    'providers' => [
        'students' => [
            'driver' => 'ebms-student',
            'model'  => App\Models\Student::class,
        ],
        'admin_users' => [
            'driver' => 'ebms-admin',
            'model'  => App\Models\AdminUser::class,
        ],
    ],

    'passwords' => [
        'students' => [
            'provider' => 'students',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'admin_users' => [
            'provider' => 'admin_users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
