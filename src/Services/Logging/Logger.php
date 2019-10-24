<?php

namespace Upaid\Elk\Services\Logging;

use Monolog\Handler\StreamHandler;
use Monolog\Logger as BaseLogger;
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
    public function __construct($name, array $handlers = [], array $processors = [])
    {
        $streamHandler = new StreamHandler($this->getPathToFile($name), BaseLogger::DEBUG);
        $streamHandler->setFormatter(new LogstashFormatter(
            config('elk.logstash.fields', []),
            config('elk.logstash.showLogType', true),
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

    /**
     * Adds a log record at the INFO level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessInfo($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::INFO, $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessNotice($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::NOTICE, $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessWarning($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::WARNING, $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessError($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::ERROR, $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessCritical($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::CRITICAL, $message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessAlert($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::ALERT, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function addBusinessEmergency($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::EMERGENCY, $message, $context);
    }

    /**
     * Adds a log record at the INFO level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessInfo($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::INFO, $message, $context);
    }

    /**
     * Adds a log record at the NOTICE level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessNotice($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::NOTICE, $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessWarn($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::WARNING, $message, $context);
    }

    /**
     * Adds a log record at the WARNING level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessWarning($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::WARNING, $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessErr($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::ERROR, $message, $context);
    }

    /**
     * Adds a log record at the ERROR level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessError($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::ERROR, $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessCrit($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::CRITICAL, $message, $context);
    }

    /**
     * Adds a log record at the CRITICAL level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessCritical($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::CRITICAL, $message, $context);
    }

    /**
     * Adds a log record at the ALERT level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessAlert($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::ALERT, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessEmerg($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::EMERGENCY, $message, $context);
    }

    /**
     * Adds a log record at the EMERGENCY level.
     *
     * This method allows for compatibility with common interfaces.
     *
     * @param  string  $message  The log message
     * @param  array   $context  The log context
     * @return bool   Whether the record has been processed
     */
    public function businessEmergency($message, array $context = [])
    {
        $context[LogContent::LOG_TYPE_KEY] = LogType::BUSINESS;

        return $this->addRecord(static::EMERGENCY, $message, $context);
    }
}