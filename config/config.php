<?php

return [
    'logstash' => [
        'serviceName' => env('ELK_SERVICE_NAME', ''),
        'bankName' => env('ELK_BANKNAME', ''),
        'channel' => env('ELK_CHANNEL', ''),
    ]
];