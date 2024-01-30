<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class SimplApi extends AbstractHelper {

    const INSTALL_API = 'api/v1/mogento/app/install';
    const PAYMENT_INIT_API = 'api/v1/mogento/payment/initiate';
    const REFUND_INIT_API = 'api/v1/mogento/order/:order_id/refund';

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
        if ($response->getSuccess()) {
            $data = $response->getData();
            return $data["redirection_url"];
        }
        return $url;
    }

    /**
     * Function to init refund
     * @param $data
     * @return string
     */
    public function initRefund($orderId, $data) {
        $endPoint = str_replace(':order_id', $orderId, self::REFUND_INIT_API);
        $response = $this->simplClient->postRequest($endPoint, $data);
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }
}
