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
    ]
];