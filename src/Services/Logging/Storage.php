<?php

namespace Upaid\Elk\Services\Logging;

class Storage
{
    public static function daily()
    {
        return storage_path('logs/' . date('Y') . '/' . date('m') . '/' . date('d') . '/');
    }
}
