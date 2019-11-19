<?php

namespace Upaid\Elk\Services\Logging\Processors;

class MaskProcessor
{
    protected $keysToMask;
    protected $patternsToMask;
    protected $mask;

    public function __construct(array $keysToMask, array $patternsToMask, string $mask = '[MASKED]')
    {
        $this->keysToMask = $keysToMask;
        $this->patternsToMask = $patternsToMask;
        $this->mask = $mask;
    }

    public function __invoke(array $record)
    {
        $this->mask($record['message']);

        if ($record['context']) {
            if (is_array($record['context'])) {
                $this->maskArray($record['context']);
            } else {
                $this->mask($record['context']);
            }
        }

        return $record;
    }

    private function maskArray(array &$content)
    {
        foreach ($content as $key => $value) {
            if (is_array($value)) {
                $this->maskArray($value);
            } else {
                $this->mask($value, $key);
            }

            $content[$key] = $value;
        }
    }

    private function mask(&$content, $key = null)
    {
        if ($key && in_array($key, $this->keysToMask)) {
            $content = $this->mask;

            return;
        }

        foreach ($this->keysToMask as $key) {
            $this->replace($key, $content);
        }
    }

    private function replace(string $key, &$content)
    {
        $content = preg_replace('/"' . $key . '":( |\t)+"/', '"' . $key . '":"', $content);
        $content = preg_replace('/"' . $key . '":"(.+?)"/', '"' . $key . '":"' . $this->mask . '"', $content);
        foreach ($this->patternsToMask as $pattern) {
            $content = preg_replace($pattern, $this->mask, $content);
        }
    }
}