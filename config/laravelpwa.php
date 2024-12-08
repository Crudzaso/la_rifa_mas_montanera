<?php

return [
    'name' => 'La Rifa Mas Montañera',
    'manifest' => [
        'name' => env('APP_NAME', 'Rifa Montañera'),
        'short_name' => 'RifaMontañera',
        'start_url' => '/',
        'background_color' => '#ffffff',
        'theme_color' => '#FFFFFF',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'green',
        'icons' => [
            '192x192' => [
                'path' => '/images/icons/android-chrome-192x192.png',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/images/icons/android-chrome-512x512.png',
                'purpose' => 'any'
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/android-chrome-512x512.png',
            '750x1334' => '/images/icons/android-chrome-512x512.png',
            '828x1792' => '/images/icons/splash-828x1792.png',
            '1125x2436' => '/images/icons/splash-1125x2436.png',
            '1242x2208' => '/images/icons/splash-1242x2208.png',
            '1242x2688' => '/images/icons/splash-1242x2688.png',
            '1536x2048' => '/images/icons/splash-1536x2048.png',
            '1668x2224' => '/images/icons/splash-1668x2224.png',
            '1668x2388' => '/images/icons/splash-1668x2388.png',
            '2048x2732' => '/images/icons/splash-2048x2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'La Rifa Mas Montañera',
                'description' => 'La oportunidad esta en tus manos',
                'url' => '/shortcutlink1',
                'icons' => [
                    "src" => "/images/icons/icon-72x72.png",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/shortcutlink2'
            ]
        ],
        'custom' => []
    ]
];
