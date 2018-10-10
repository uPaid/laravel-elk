<?php

namespace Upaid\Elk\Services;

class TraceSpan
{
    private $spanId;
    private $traceId;
    private $parentSpanId;

    public function getSpanId()
    {
        if ($this->spanId) {
            return $this->spanId;
        }

        $this->spanId = Request()->getMethod() . ' ' . Request()->path();

        return $this->spanId;
    }

    public function getParentSpanId()
    {
        if ($this->parentSpanId) {
            return $this->parentSpanId;
        }

        $this->parentSpanId = $this->getHeader('span_id') ?: $this->getHeader('X-B3-SpanId');

        return $this->parentSpanId;
    }

    private function getHeader($name)
    {
        return (string) Request()->headers->get($name);
    }

    public function getTraceId()
    {
        if ($this->traceId) {
            return $this->traceId;
        }

        $traceId = $this->getHeader('trace_id') ?: $this->getHeader('X-B3-TraceId');

        if (!$traceId) {
            $traceId = $this->generateTraceId();
        }

        $this->traceId = $traceId;

        return $this->traceId;
    }

    private function generateTraceId()
    {
        $traceGenerator = TraceGenerator::getInstance();

        return $traceGenerator->getTraceId();
    }
}
