<?php


namespace Upaid\Elk\Services\Logging\Processors;

use \Monolog\Processor\IntrospectionProcessor as BaseIntrospectionProcessor;


class IntrospectionProcessor extends BaseIntrospectionProcessor
{
    public function __invoke(array $record)
    {
        $processedRecord = parent::__invoke($record);

        $record['class'] = $processedRecord['extra']['class'];

        return $record;
    }
}