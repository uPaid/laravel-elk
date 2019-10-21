<?php

namespace Upaid\Elk\Services\Logging;

class LogContent
{
    /**
     * <code>[logKey => recordKey]</code>
     */
    private const DIRECT_RECORD_KEYS = [
        'trace' => 'trace',
        'span' => 'span',
        'parent' => 'parent',
        'msg' => 'message',
        'severity' => 'level_name',
    ];
    /**
     * <code>[logKey => recordKey]</code>
     */
    private const OPTIONAL_RECORD_KEYS = [
        'class' => 'class',
        'exception' => 'exception',
        'extra' => 'extra',
    ];
    private const CONTEXT_KEYS = [
        'user.phone',
        'user.email',
        'card.id',
        'log_type',
    ];
    private const KEY_ORDER = [
        'timestamp',
        'severity',
        'msg',
        'span',
        'class',
        'exception',
        'trace',
        'parent',
        'bank.name',
        'environment',
        'service',
        'channel',
        'user.phone',
        'user.email',
        'card.id',
        'context',
        'log_type',
    ];
    private const CONTEXT_KEY = 'context';
    const TECHNICAL_LOG = 'technical';
    const BUSINESS_LOG = 'business';
    /**
     * @var array
     */
    private $content;

    public function addFromRecord(array $record): void
    {
        $this->addDirectValues($record);
        $this->addOptionalValues($record);
    }

    private function addDirectValues(array $record): void
    {
        foreach (self::DIRECT_RECORD_KEYS as $logKey => $recordKey) {
            $value = $record[$recordKey] ?? '';
            $this->add($logKey, $value);
        }
    }

    public function add(string $key, $value): void
    {
        $this->content[$key] = $value ?? '';
    }

    private function addOptionalValues(array $record): void
    {
        foreach (self::OPTIONAL_RECORD_KEYS as $logKey => $recordKey) {
            if (!empty($record[$recordKey])) {
                $this->add($logKey, $record[$recordKey]);
            }
        }
    }

    public function addFromContext(array $context): void
    {
        foreach (self::CONTEXT_KEYS as $contextKey) {
            if (isset($context[$contextKey])) {
                $this->add($contextKey, $context[$contextKey]);
                unset($context[$contextKey]);
            }
        }

        $this->checkLogType();

        if (!empty($context)) {
            $this->add(self::CONTEXT_KEY, $context);
        }
    }

    private function checkLogType()
    {
        $logTypeKey = 'log_type';
        $options = [self::TECHNICAL_LOG, self::BUSINESS_LOG];

        if (!isset($this->content[$logTypeKey]) || !in_array($this->content[$logTypeKey], $options)) {
            $this->content[$logTypeKey] = self::TECHNICAL_LOG;
        }
    }

    public function getSorted(): array
    {
        $order = array_intersect(self::KEY_ORDER, array_keys($this->content));

        return array_replace(array_flip($order), $this->content);
    }
}