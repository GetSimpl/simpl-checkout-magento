<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class SimplApi extends AbstractHelper {

    const INSTALL_API = 'api/v1/mogento/app/install';
    const PAYMENT_INIT_API = 'api/v1/mogento/payment/initiate';

    /**
     * @var SimplClient
     */
    protected $simplClient;

    public function __construct(
        SimplClient $simplClient,
        Context $context
    ) {
        $this->simplClient = $simplClient;
        parent::__construct($context);
    }

    /**
     * @param $response
     * @return bool
     */
    private function isSuccess($response) {
        if (isset($response["data"]["success"]) and $response["data"]["success"] == true) {
            return true;
        }
        return false;
    }

    /**
     * @param $response
     * @return null|string
     */
    private function getErrorMessage($response) {
        if (isset($response["data"]["error"]) and isset($response["data"]["error"]["message"])) {
            return $response["data"]["error"]["message"];
        }
        return NULL;
    }

    /**
     * API to install plugin
     * @param string $secret
     * @param string $clientId
     * @return array
     */
    public function install(string $secret, string $clientId) {
        $this->simplClient->setClientId($clientId);
        $this->simplClient->setSecret($secret);
        $response = $this->simplClient->postRequest(self::INSTALL_API);
        if ($response->isSuccess()) {
            return [
                'status' => true,
                'message' => 'Congratulations! Valid Credentials'
            ];
        }
        return [
            'status' => false,
            'message' => $response->getErrorMessage()
        ];
    }

    /**
     * Function Integrate API to init payment.
     * @param $data
     * @return string
     */
    public function initPayment($data) {
        $url = '';
        $response = $this->simplClient->callSimplApi(self::PAYMENT_INIT_API, $data);
        if ($this->isSuccess($response)) {
            $url = $response["data"]["success"]["data"]["redirection_url"];
        }
        return $url;
    }
}
