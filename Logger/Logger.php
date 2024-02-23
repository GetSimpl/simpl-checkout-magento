<?php

namespace Simpl\Checkout\Logger;

use DateTimeZone;
use Simpl\Checkout\Helper\Config;
use Simpl\Checkout\Helper\Alert as SimplAlert;

class Logger extends \Monolog\Logger
{
    protected $config;

    protected $simplAlert;

    public function __construct(
        Config $config,
        SimplAlert $simplAlert,
        string $name,
        array $handlers = [],
        array $processors = [],
        ?DateTimeZone $timezone = null
    ) {
        $this->config = $config;
        $this->simplAlert = $simplAlert;
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
        $stacktrace = $context['stacktrace'] ?? null;
        $this->simplAlert->alert($message, "ERROR" , $stacktrace);
        parent::error($message, $context);
    }

    public function critical($message, array $context = []): void
    {
        $stacktrace = $context['stacktrace'] ?? null;
        $this->simplAlert->alert($message, "CRITICAL" , $stacktrace);
        parent::critical($message, $context);
    }
}
