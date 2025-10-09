<?php

$time = [
    'minute',
    'minutes',
    'hours',
    'days'
];

return [
    'time' => $time,

    'response_for' => [
        'api' => [
            'attempts' => 10,
        ],
        'global' => [
            'attempts' => 25,
        ],
        'auth' => [
            'attempts' => 5,
            'time' => array_search('minutes', $time) . ':5'
        ]
    ]
];
