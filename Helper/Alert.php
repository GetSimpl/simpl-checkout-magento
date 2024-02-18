<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Simpl\Checkout\Logger\Logger;

class Alert extends AbstractHelper
{
    protected $config;

    protected $simplApi;

    protected $url;

    protected $logger;

    public function __construct(
        Config $config,
        SimplApi $simplApi,
        UrlInterface $url,
        Logger $logger,
        Context $context
    )
    {
        $this->simplApi = $simplApi;
        $this->config = $config;
        $this->url = $url;
        $this->logger = $logger;
        parent::__construct($context);
    }



    /**
     * @param $message
     * @param $type
     * @param $stacktrace
     * @return bool
     */
    public function alert($message, $type, $stacktrace ) {

        if (!$this->config->isLogEnabled()) {
            return false;
        }

        $this->logger->info($message, ["type" => $type, "stacktrace" => $stacktrace]);
        $data["error"]["message"] = $message;
        $data["error"]["level"] = $type;
        $data["error"]["stacktrace"] = $stacktrace;
        $data["merchant_details"] = [
            "domain" => $this->config->getDomain(),
            "client_id" => $this->config->getClientId()
        ];
        $data["current_url"] = $this->url->getCurrentUrl();
        $data["environment"] = $this->config->getIntegrationMode();
        $data["extension_version"] = $this->config->getVersion();
        $data["device"]["name"] = $_SERVER['HTTP_USER_AGENT'];
        $data["device"]["ip"] = $_SERVER['REMOTE_ADDR'];

        return $this->simplApi->alert($data);
    }
}
