<?php

namespace Simpl\Checkout\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
    /**
     * Logging level
     *
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * Name of the log file
     *
     * @var string
     */
    protected $fileName = '/var/log/simpl.log';
}
