<?php

namespace Upaid\Elk\Services\Logging\Formatters;

use Monolog\Formatter\NormalizerFormatter;
use Upaid\Elk\Services\Logging\LogContent;

class LogstashFormatter extends NormalizerFormatter
{
    /**
     * @var array
     */
    private $fields;
    /**
     * @var string the name of bank for the Logstash log message, used to fill the @bankname field
     */
    protected $bankname;
    /**
     * @var string an application name for the Logstash log message, used to fill the @service field
     */
    protected $serviceName;
    protected $environment;
    protected $channel;
    /**
     * @var bool
     */
    private $showLogType;

    /**
     * @param  string  $serviceName  the application that sends the data, used as the "service" field of logstash
     * @param  string  $bankname     the bank name, used as the "bankame" field of logstash
     * @param  string  $environment  the environment, used as the "environment" field of logstash
     * @param  string  $channel      the channel, used in filebeat to distinguish the application
     */
    public function __construct(array $fields, bool $showLogType, string $serviceName, string $bankname, ?string $channel, string $environment)
    {
        // logstash requires a ISO 8601 format date with optional millisecond precision.
        parent::__construct('Y-m-d\TH:i:s.uP');
        $this->bankname = $bankname;
        $this->serviceName = $serviceName;
        $this->environment = $environment;
        $this->channel = $channel;
        $this->fields = $fields;
        $this->showLogType = $showLogType;
    }

    /**
     * {@inheritdoc}
     */
    public function format(array $record): string
    {
        $record = parent::format($record);

        $content = new LogContent($this->fields, $this->showLogType);

        $content->addFromRecord($record);

        foreach ($this->getGlobalVariables() as $key => $value) {
            $content->add($key, $value);
        }

        return $this->printLog($content->getSorted());

    }

    private function getGlobalVariables(): array
    {
        return [
            'bank.name' => $this->bankname,
            'environment' => $this->environment,
            'service' => $this->serviceName,
            'channel' => $this->channel,
        ];
    }

    private function printLog(array $content): string
    {
        return $this->toJson($content) . "\n";
    }
}