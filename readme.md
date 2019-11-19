# ELK

Laravel logger for formatting logs for ELK.<br/>
Adds support for trace / span id according to the format used by Spring Cloud Sleuth.
Adds also a few additional fields.

## Installation

#### Via Composer

``` bash
$ composer require upaid/elk
```

#### Publish the config

Run the following command to publish the package config file:

```
php artisan vendor:publish --provider="Upaid\Elk\Providers\ElkServiceProvider"
```

#### Add keys to .env
```
ELK_SERVICE_NAME='app name'
ELK_BANKNAME='bank name'
ELK_CHANNEL='channel for recognizing applications by logstash'
```
#### Or override config file
```
<?php

return [
    'logstash' => [
        'serviceName' => 'app name',
        'bankName' => 'bank name',
        'channel' => 'channel for recognizing applications by logstash',
        'showLogType' => true,
    ]
];
```
#### Changing default log fields and they order
To change default log fields or they order override `fields` property in config file
```
<?php

return [
    'logstash' => [
        'serviceName' => 'app name',
        'bankName' => 'bank name',
        'channel' => 'channel for recognizing applications by logstash',
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
    ]
];
```

#### Hiding sensitive data
In order to remove sensitive data from the log file content you can add `mask.fields` property to the config file
and specify the values of which fields should be hidden. Fields values will be replaced with `mask.replacement` property.
It's also possible to hide values according to the patterns specified in `mask.patterns`. The example represents
replacement of card number.
```
<?php

return [
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
```

#### Add logging channel

Add elk channel to the channels array in the config/logging.php config file as follows:

``` php
'channels' => [
    'elk' => [
        'driver' => 'custom',
        'via' => \Upaid\Elk\Services\Logging\CreateCustomLogger::class,
        'log_name' => 'global'
    ],
    
    ...
```

In the same file set default log channel to elk:

``` php
return [
    'default' => 'elk',
    
    ...
```

or when you want to use stack channel (default channel is 'stack') add elk to stack channel:

``` php
'channels' => [

    ...
    
    'stack' => [
        'driver' => 'stack',
        'channels' => ['elk'],
    ],

    ...
```

## Usage

Standard logging in Laravel (to global.json)
``` php
Log::debug('An informational message.');
```

Logging to custom log file name (in this case to testlog.json):

``` php
use \Upaid\Elk\Services\Logging\Logger;

$logger = new Logger('testlog');
$logger->info('Hello World!');
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.
