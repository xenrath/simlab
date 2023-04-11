<?php

$title = "Simlab Bhamada";
$description = "Sistem Informasi Manajemen Laboratorium | Universitas Bhamada Slawi";

return [
    'meta' => [
        'defaults'       => [
            'title'        => $title,
            'titleBefore'  => false,
            'description'  => $description,
            'separator'    => ' | ',
            'keywords'     => [
                'Sistem Informasi Peminjaman Alat Laboratorium',
                'Sistem Informasi Manajemen Laboratorium',
                'Sistem Peminjaman Alat Lab',
                'Peminjaman Alat Lab',
                'SIMLAB',
                'Universitas Bhamada Slawi',
                'Stikes Bhamada Slawi',
                'Bhamada Slawi',
                'Laboratorium Terpadu Universitas Bhamada Slawi',
                'Lab Terpadu Bhamada'
            ],
            'canonical'    => null, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'robots'       => false, // Set to 'all', 'none' or any combination of index/noindex and follow/nofollow
        ],
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => $title,
            'description' => $description,
            'url'         => null, // Set null for using Url::current(), set false to total remove
            'type'        => false,
            'site_name'   => 'Simlab Bhamada',
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
            //'card'        => 'summary',
            //'site'        => '@LuizVinicius73',
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => $title,
            'description' => $description,
            'url'         => null,
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
