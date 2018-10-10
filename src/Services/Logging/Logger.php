<?php

namespace Upaid\Elk\Services\Logging;

use Monolog\Handler\StreamHandler;
use \Monolog\Logger as BaseLogger;
use Upaid\Elk\Services\Logging\Formatters\LogstashFormatter;
use Upaid\Elk\Services\Logging\Processors\IntrospectionProcessor;
use Upaid\Elk\Services\Logging\Processors\MaskProcessor;
use Upaid\Elk\Services\Logging\Processors\TraceSpanProcessor;

class Logger extends BaseLogger
{
    /**
     * Logger constructor.
     * @param $name Log file name
     * @param array $handlers
     * @param array $processors
     * @throws \Exception
     */
    public function __construct($name, array $handlers = array(), array $processors = array())
    {
        $streamHandler = new StreamHandler($this->getPathToFile($name), BaseLogger::DEBUG);
        $streamHandler->setFormatter(new LogstashFormatter(
            config('elk.logstash.serviceName'),
            config('elk.logstash.bankName'),
            config('elk.logstash.channel'),
            config('app.env')
        ));

        parent::__construct('custom', [$streamHandler], [
            app(TraceSpanProcessor::class),
            new MaskProcessor(),
            new IntrospectionProcessor()
        ]);
    }

    public function getPathToFile($name)
    {
        return Storage::daily() . $name . '.json';
    }
}