# ELK

Laravel logger for formatting logs for ELK.<br/>
Adds support for trace / span id according to the format used by Spring Cloud Sleuth.
Adds also a few additional fields.

## Installation

#### Via Composer

``` bash
$ composer require upaid/elk
```

#### Add service provider

Add the service provider to the providers array in the config/app.php config file as follows:

``` php
'providers' => [

    ...

    Upaid\Elk\Providers\ElkServiceProvider::class,
]
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

## Usage

``` php
use \Upaid\Elk\Services\Logging\Logger;

$logger = new Logger('testlog');
$logger->info('Hello World!');
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.
