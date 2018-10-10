<?php

namespace Upaid\Elk\Services;

use Monolog\Processor\UidProcessor;

class TraceGenerator
{
    protected $traceId = NULL;

    protected static $instance = null;

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->traceId = $this->generateTraceId();
    }

    private function generateTraceId()
    {
        $uidProcessor = new UidProcessor(16);

        return $uidProcessor->getUid();
    }

    public function getTraceId(): string
    {
        return $this->traceId;
    }
}