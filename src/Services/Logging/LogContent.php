<?php

namespace Upaid\Elk\Services\Logging;

class LogContent
{
    const TIMESTAMP_KEY = 'timestamp';
    const CONTEXT_KEY = 'context';
    const LOG_TYPE_KEY = 'log_type';
    /**
     * <code>[logKey => recordKey]</code>
     */
    private const DEFAULT_RECORD_KEYS = [
        'timestamp',
        'severity' => 'level_name',
        'msg' => 'message',
        'span',
        'class' => 'class',
        'exception' => 'exception',
        'trace',
        'parent',
        'bank.name',
        'environment',
        'service',
        'channel',
        'context' => [
            'user.phone',
            'user.email',
            'card.id',
        ],
        'extra',
        'log_type',
    ];
    /**
     * @var array
     */
    private $fields;
    /**
     * @var array
     */
    private $content;

    public function __construct(array $fields, bool $withLogType = true)
    {
        if (!$fields) {
            $fields = self::DEFAULT_RECORD_KEYS;
        }

        $this->fields = $this->prepareFields($fields);

        $this->checkField(self::TIMESTAMP_KEY, true);

        if ($withLogType) {
            $this->checkField(self::LOG_TYPE_KEY);
        }
    }

    private function prepareFields(array $fields): array
    {
        $preparedFields = [];

        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $value = $this->prepareFields($value);
            } elseif (is_int($key) && is_string($value)) {
                $key = $value;
            }
            $preparedFields[$key] = $value;
        }

        return $preparedFields;
    }

    private function checkField(string $field, bool $prepend = false): void
    {
        $key = array_search($field, $this->fields);
        if ($key !== false) {
            return;
        }
        if ($prepend) {
            $this->fields = [$field => $field] + $this->fields;
        } else {
            $this->fields[$field] = $field;
        }
    }

    public function addFromRecord(array $record): void
    {
        $this->addByFields($record, $this->fields);

        if (isset($record[self::CONTEXT_KEY])) {
            $this->addFromContext($record[self::CONTEXT_KEY]);
        }

        $this->addTimestamp($record);
        $this->addLogType($record);
    }

    private function addByFields(array $record, array $fields): void
    {
        foreach ($fields as $logKey => $recordKey) {
            if (is_array($recordKey)) {
                $this->addByFields($record, $recordKey);
                continue;
            } else {
                $value = $record[$recordKey] ?? '';
                $this->add($logKey, $value);
            }
        }
    }

    public function add(string $key, $value): void
    {
        $this->content[$key] = $value ?? '';
    }

    private function addFromContext(array $context): void
    {
        if (!isset($this->fields[self::CONTEXT_KEY])) {
            return;
        }

        foreach ($this->fields[self::CONTEXT_KEY] as $key => $value) {
            if (isset($context[$value])) {
                $this->add($key, $context[$value]);
                unset($context[$value]);
            }
        }

        if (!empty($context)) {
            $this->add(self::CONTEXT_KEY, $context);
        }
    }

    private function addTimestamp(array $record): void
    {
        $timestampKey = array_search(self::TIMESTAMP_KEY, $this->fields);
        if ($timestampKey === false) {
            return;
        }

        $value = $record[self::TIMESTAMP_KEY] ?? gmdate('c');

        $this->add($timestampKey, $value);
    }

    private function addLogType(array $record): void
    {
        $logKey = array_search(self::LOG_TYPE_KEY, $this->fields);
        if ($logKey === false) {
            return;
        }

        $value = $record[self::LOG_TYPE_KEY] ?? LogType::TECHNICAL;
        $value = $value === LogType::BUSINESS ?: LogType::TECHNICAL;

        $this->add($logKey, $value);
    }

    public function getSorted(): array
    {
        $fieldsOrder = $this->getOrder($this->fields);
        $order = array_intersect($fieldsOrder, array_keys($this->content));

        return array_replace(array_flip($order), $this->content);
    }

    public function getOrder(array $fields): array
    {
        $order = [];
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                $order[] = $key;
                $order = array_merge($order, $this->getOrder($value));
            } else {
                $order[] = $key;
            }
        }

        return $order;
    }
}