<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Authentication Configuration
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => 'masyarakat',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Disini kita mendefinisikan dua guard:
    | - web → untuk masyarakat
    | - petugas → untuk petugas/admin
    |
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'masyarakat', // ✅ diperbaiki dari 'users'
        ],

        'petugas' => [
            'driver' => 'session',
            'provider' => 'petugas',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Provider menentukan model mana yang digunakan untuk tiap guard.
    |
    */
    'providers' => [
        'masyarakat' => [ // ✅ nama provider disesuaikan
            'driver' => 'eloquent',
            'model' => App\Models\Masyarakat::class,
        ],

        'petugas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Petugas::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Reset Settings
    |--------------------------------------------------------------------------
    */
    'passwords' => [
        'masyarakat' => [
            'provider' => 'masyarakat',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'petugas' => [
            'provider' => 'petugas',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */
    'password_timeout' => 10800,
];
