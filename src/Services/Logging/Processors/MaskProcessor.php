<?php

namespace Upaid\Elk\Services\Logging\Processors;

class MaskProcessor
{
    const MASKED = '[MASKED]';

    protected $keysToMask = ['card_no', 'password', 'password_confirmation'];

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
            $content = self::MASKED;
            return;
        }

        $content = preg_replace('/"card_no":"\d{12}/', '"card_no":"' . self::MASKED, $content);

        foreach ($this->keysToMask as $key) {
            $this->replace($key, $content);
        }
    }

    private function replace(string $key, &$content)
    {
        $content = preg_replace('/"' . $key . '":( |\t)+"/', '"' . $key . '":"', $content);
        $content = preg_replace('/"' . $key . '":"(.+?)"/', '"' . $key . '":"' . self::MASKED . '"', $content);
    }
}