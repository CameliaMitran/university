<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
        'session' => [
            'driver' => 'jwt',
            'provider' => 'sessions',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ],
        'sessions' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Session::class
        ]
    ]
];
