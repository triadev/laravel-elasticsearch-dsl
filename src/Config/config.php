<?php

return [
    'index' => env('LARAVEL_ELASTICSEARCH_DSL_INDEX', 'default_index'),
    'metrics' => [
        'enabled' => env('LARAVEL_ELASTICSEARCH_DSL_METRICS', false),
        'buckets' => [
            'search' => [
                5, 10, 25, 50, 75, 100, 250, 500, 750, 1000, 2500, 5000, 7500, 10000, 15000, 25000, 50000
            ],
            'suggest' => [
                5, 10, 25, 50, 75, 100, 250, 500, 750, 1000, 2500, 5000, 7500, 10000, 15000, 25000, 50000
            ]
        ]
    ]
];
