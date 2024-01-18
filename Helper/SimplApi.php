<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class SimplApi extends AbstractHelper
{

    const INSTALL_API = 'api/v1/mogento/app/install';

    /**
     * @var SimplClient
     */
    protected $simplClient;

    public function __construct(
        SimplClient $simplClient,
        Context $context
    )
    {
        $this->simplClient = $simplClient;
        parent::__construct($context);
    }

    /**
     * API to install plugin
     * @param $secret
     * @return array
     */
    public function install($secret,$clientId) {
        $this->simplClient->setClientId($clientId);
        $this->simplClient->setSecret($secret);
        $response = $this->simplClient->callSimplApi(self::INSTALL_API);
        if (isset($response["data"]["success"]) and $response["data"]["success"] == true) {
            return [
                'status' => true,
                'message' => 'Congratulations! Valid Credentials'
            ];
        } elseif (isset($response["data"]["error"]) and isset($response["data"]["error"]["message"])) {
            return [
                'status' => false,
                'message' => $response["data"]["error"]["message"]
            ];
        }
        return [
            'status' => false,
            'message' => 'Invalid Credentials!'
        ];
    }
}
