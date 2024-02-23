<?php

namespace Simpl\Checkout\Logger;

use DateTimeZone;
use Simpl\Checkout\Helper\Config;

class Logger extends \Monolog\Logger
{
    protected $config;

    public function __construct(
        Config $config,
        string $name,
        array $handlers = [],
        array $processors = [],
        ?DateTimeZone $timezone = null
    ) {
        $this->config = $config;
        parent::__construct($name, $handlers, $processors, $timezone);
    }

    public function info($message, array $context = []): void
    {
        if ($this->config->isLogEnabled()) {
            parent::info($message, $context);
        }
    }

    public function error($message, array $context = []): void
    {
        if ($this->config->isLogEnabled()) {
            parent::error($message, $context);
        }
    }

    public function critical($message, array $context = []): void
    {
        if ($this->config->isLogEnabled()) {
            parent::critical($message, $context);
        }
    }
}
