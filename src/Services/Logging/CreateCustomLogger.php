<?php


namespace Upaid\Elk\Services\Logging;


class CreateCustomLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        return new Logger($config['log_name']);
    }
}
