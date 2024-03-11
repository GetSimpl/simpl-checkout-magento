<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Simpl\Checkout\Api\Data\Order\TransactionDataInterface;
use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;
use Magento\Framework\UrlInterface;
use Simpl\Checkout\Logger\Logger;

class SimplApi extends AbstractHelper
{

    const INSTALL_API = 'api/v1/magento/app/install';
    const PAYMENT_INIT_API = 'api/v1/magento/payment/initiate';
    const REFUND_INIT_API = 'api/v1/magento/order/:order_id/refund';
    const CANCEL_INIT_API = 'api/v1/magento/order/:order_id/cancel';
    const FETCH_PAYMENT_API = 'api/v1/magento/payment_order/';
    const FETCH_REFUND_API = 'api/v1/magento/refund/';
    const ALERT_API = 'api/v1/magento/alert/track';
    const EVENT_API = 'api/v1/magento/event/track';

    /**
     * @var SimplClient
     */
    protected $simplClient;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * AuthHelper
     */
    protected $authHelper;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(
        SimplClient $simplClient,
        Config $config,
        UrlInterface $url,
        AuthHelper $authHelper,
        Logger $logger,
        Context $context
    ) {
        $this->simplClient = $simplClient;
        $this->config = $config;
        $this->url = $url;
        $this->authHelper = $authHelper;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * API to install plugin
     * @param string $secret
     * @param string $clientId
     * @return array
     */
    public function install(string $secret, string $clientId)
    {
        $this->authHelper->setClientId($clientId);
        $this->authHelper->setSecret($secret);
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
    public function initPayment($data)
    {
        $url = '';
        $response = $this->simplClient->postRequest(self::PAYMENT_INIT_API, $data);
        if ($response->isSuccess()) {
            $data = $response->getData();
            return $data["redirection_url"];
        }
        return $url;
    }

    /**
     * @param $orderId
     * @param $data
     * @return bool
     */
    public function cancel($orderId, $data)
    {
        $endPoint = str_replace(':order_id', $orderId, self::CANCEL_INIT_API);
        $response = $this->simplClient->postRequest($endPoint, $data);
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }

    /**
     * Function to init refund
     * @param $data
     * @return string
     */
    public function initRefund($orderId, $data)
    {
        $endPoint = str_replace(':order_id', $orderId, self::REFUND_INIT_API);
        $response = $this->simplClient->postRequest($endPoint, $data);
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }

    /**
     * @param $creditMemoId
     * @param $orderId
     * @param $transactionId
     * @param $status
     * @return bool
     */
    public function validateRefund($creditMemoId, $orderId, $transactionId, $status)
    {

        $apiEndPoint = self::FETCH_REFUND_API . $transactionId;
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

    /**
     * @param $order
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     */
    public function validatePayment($order, $payment, $transaction)
    {

        $apiEndPoint = self::FETCH_PAYMENT_API . $transaction->getId();
        $response = $this->simplClient->getRequest($apiEndPoint);
        if ($response->isSuccess()) {
            $data = $response->getData();
            if ($data["payment_order_id"] != $transaction->getId()) {
                return false;
            } elseif ($data["payment_mode"] != $payment->getMode()) {
                return false;
            } elseif ($data["status"] != $payment->getStatus()) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * @param array $param
     * @return string
     */
    public function getRedirectUrl($param = [])
    {
        return $this->_getUrl('simpl/index/orderstatusredirect', $param);
    }

    /**
     * @param $data
     * @return bool
     */
    public function alert($data)
    {

        $endPoint = self::ALERT_API;
        $response = $this->simplClient->postRequest($endPoint, $data);
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }

    /**
     * @param $name
     * @param $payload
     * @param $type
     * @return bool
     */
    public function event($name, $payload, $type, $version = '1.0.0')
    {

        $endPoint = self::EVENT_API;
        $data["name"] = $name;
        $data["version"] = $version;
        $data["type"] = $type;
        $data["payload"] = $payload;
        $response = $this->simplClient->postRequest($endPoint, $data);
        if ($response->isSuccess()) {
            return true;
        }
        return false;
    }
}
