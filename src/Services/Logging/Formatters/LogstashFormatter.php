<?php


namespace Upaid\Elk\Services\Logging\Formatters;


use Monolog\Formatter\NormalizerFormatter;

class LogstashFormatter extends NormalizerFormatter
{
    const USER_PHONE = 'user.phone';
    const USER_EMAIL = 'user.email';
    const CARD_ID = 'card.id';

    /**
     * @var string the name of bank for the Logstash log message, used to fill the @bankname field
     */
    protected $bankname;

    /**
     * @var string an application name for the Logstash log message, used to fill the @service field
     */
    protected $serviceName;

    /**
     * @var string the key for 'extra' fields from the Monolog record
     */
    protected $extraKey = 'extra';

    /**
     * @var string the key for 'context' fields from the Monolog record
     */
    protected $contextKey = 'context';

    protected $environment;

    protected $channel;

    /**
     * @param string  $serviceName   the application that sends the data, used as the "service" field of logstash
     * @param string  $bankname      the bank name, used as the "bankame" field of logstash
     * @param string  $environment   the environment, used as the "environment" field of logstash
     * @param string  $channel       the channel, used in filebeat to distinguish the application
     */
    public function __construct(string $serviceName, string $bankname, ?string $channel, string $environment)
    {
        // logstash requires a ISO 8601 format date with optional millisecond precision.
        parent::__construct('Y-m-d\TH:i:s.uP');
        $this->bankname = $bankname;
        $this->serviceName = $serviceName;
        $this->environment = $environment;
        $this->channel = $channel;
    }
    /**
     * {@inheritdoc}
     */
    public function format(array $record): string
    {
        $record = parent::format($record);

        if (empty($record['timestamp'])) {
            $record['timestamp'] = gmdate('c');
        }

        $message = [
            'timestamp' => $record['timestamp'],
        ];

        $message['trace'] = $record['trace'] ?? '';

        $message['span'] = $record['span'] ?? '';

        $message['parent'] = $record['parent'] ?? '';

        $message['rest'] = $record['message'] ?? '';

        if (isset($record['class'])) {
            $message['class'] = $record['class'];
        }

        if (isset($record['exception'])) {
            $message['exception'] = $record['exception'];
        }

        $message['bank.name'] = $this->bankname;
        $message['environment'] = $this->environment;
        $message['channel'] = $this->channel;

        $message['severity'] = $record['level_name'] ?? '';

        $message['service'] = $this->serviceName;

        if (!empty($record['extra'])) {
            $message[$this->extraKey] = $record['extra'];

        }

        if (isset($record['context'][self::USER_PHONE])) {
            $message[self::USER_PHONE] = $record['context'][self::USER_PHONE];
            unset($record['context'][self::USER_PHONE]);
        }

        if (isset($record['context'][self::USER_EMAIL])) {
            $message[self::USER_EMAIL] = $record['context'][self::USER_EMAIL];
            unset($record['context'][self::USER_EMAIL]);
        }

        if (isset($record['context'][self::CARD_ID])) {
            $message[self::CARD_ID] = $record['context'][self::CARD_ID];
            unset($record['context'][self::CARD_ID]);
        }

        if (!empty($record['context'])) {
            $message[$this->contextKey] = $record['context'];
        }

        $message['type'] = 'log';

        return $this->toJson($message) . "\n";
    }
}