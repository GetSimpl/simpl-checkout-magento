<?php

namespace Simpl\Checkout\Logger;

use Simpl\Checkout\Helper\Config;
use Simpl\Checkout\Helper\Alert as SimplAlert;
use Monolog\Logger as MonologLogger;

class Logger extends MonologLogger
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var SimplAlert
     */
    protected $simplAlert;

    /**
     * Logger constructor.
     *
     * @param Config      $config
     * @param SimplAlert  $simplAlert
     * @param string      $name
     * @param array       $handlers
     * @param array       $processors
     */
    public function __construct(
        Config $config,
        SimplAlert $simplAlert,
        string $name,
        array $handlers = [],
        array $processors = []
    ) {
        $this->config = $config;
        $this->simplAlert = $simplAlert;
        parent::__construct($name, $handlers, $processors);
    }

    /**
     * Log an info message if logging is enabled.
     *
     * @param mixed $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = []): void
    {
        if ($this->config->isLogEnabled()) {
            parent::info($message, $context);
        }
    }

    /**
     * Log an error message and trigger an alert.
     *
     * @param mixed $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = []): void
    {
        $stacktrace = $context['stacktrace'] ?? null;
        $this->simplAlert->alert($message, "ERROR", $stacktrace);
        parent::error($message, $context);
    }

    /**
     * Log a critical message and trigger an alert.
     *
     * @param mixed $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = []): void
    {
        $stacktrace = $context['stacktrace'] ?? null;
        $this->simplAlert->alert($message, "CRITICAL", $stacktrace);
        parent::critical($message, $context);
    }
}
