<?php

namespace Upaid\Elk\Services\Logging\Processors;

use Upaid\Elk\Services\TraceSpan;

class TraceSpanProcessor
{
    protected $header;

    public function __construct(TraceSpan $header)
    {
        $this->header = $header;
    }

    public function __invoke($record)
    {
        $record['trace'] = $this->header->getTraceId();
        $record['span'] = $this->header->getSpanId();
        $record['parent'] = $this->header->getParentSpanId();

        return $record;
    }
}
