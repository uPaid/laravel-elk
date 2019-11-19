<?php

return [
    'logstash' => [
        'serviceName' => env('ELK_SERVICE_NAME', ''),
        'bankName' => env('ELK_BANKNAME', ''),
        'channel' => env('ELK_CHANNEL', ''),
        'fields' => [
            'timestamp',
            'severity' => 'level_name',
            'msg' => 'message',
            'span',
            'class' => 'class',
            'exception' => 'exception',
            'trace',
            'parent',
            'bank.name',
            'environment',
            'service',
            'channel',
            'context' => [
                'user.phone',
                'user.email',
                'card.id',
            ],
            'extra',
            'log_type',
        ],
        'showLogType' => true,
    ],
    'mask' => [
        'replacement' => '[MASKED]',
        'fields' => [
            'password',
            'new_password',
            'password_confirmation',
            'card_no',
            'cvc',
        ],
        'patterns' => [
            '/^(?:4[0-9]{12}(?:[0-9]{3})?|[25][1-7][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/',
        ],
    ]
];