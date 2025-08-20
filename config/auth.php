<?php

return [

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Admin & user biasa (tabel `users`)
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guru (tabel `teachers`)
        'teacher' => [
            'driver' => 'session',
            'provider' => 'teachers',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
            'hash' => false,
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],
        'teachers' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Teacher::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'teachers' => [
            'provider' => 'teachers',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
