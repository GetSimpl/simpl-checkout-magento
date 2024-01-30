<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class SimplApi extends AbstractHelper {

    const INSTALL_API = 'api/v1/mogento/app/install';
    const PAYMENT_INIT_API = 'api/v1/mogento/payment/initiate';
    const FETCH_REFUND_API = 'api/v1/magento/refund/';

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
     * @param $creditMemoId
     * @param $orderId
     * @param $transactionId
     * @param $status
     * @return bool
     */
    public function validateRefund($creditMemoId, $orderId, $transactionId, $status ) {

        $apiEndPoint = self::FETCH_REFUND_API . $creditMemoId;
        $response = $this->simplClient->getRequest($apiEndPoint);
        if ($response->isSuccess()) {
            $data = $response->getData();
            if ($data["order_id"] != $orderId) {
                return false;
            } elseif ($data["transaction_id"] != $transactionId) {
                return false;
            } elseif ($data["status"] != $status) {
                return false;
            }
            return true;
        }
        return false;
    }
}
