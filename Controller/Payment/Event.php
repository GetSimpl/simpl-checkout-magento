<?php

namespace Simpl\Checkout\Controller\Payment;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Sales\Model\Order;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Logger\Logger;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;


class Event implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var SimplApi
     */
    protected $simplApi;

    /**
     * @var Http
     */
    protected $request;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Json
     */
    protected $json;

    public function __construct(
        JsonFactory $jsonFactory,
        Order $order,
        SimplApi $simplApi,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        Http $request,
        Logger $logger,
        Json $json
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->order = $order;
        $this->simplApi = $simplApi;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->request = $request;
        $this->logger = $logger;
        $this->json = $json;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data["status"] = false;

        $event = $this->getParam('event');
        $payload = $this->getFingerprint();
        $payload["user_details"] = $this->getUserDetails();

        switch ($event) {
            case "platform_checkout_pageview":
                $payload["cart_details"] = $this->getCartDetails();
                $this->simplApi->event("checkout_started",$payload,"platform_checkout_pageview");
                break;

            case "platform_payment_mode_selected":
                $payload["cart_details"] = $this->getCartDetails();
                if ($this->checkoutSession->getQuote()->getPayment())
                    $payload["payment_mode_details"] = $this->checkoutSession->getQuote()->getPayment()->getData();
                $this->simplApi->event("payment_selected",$payload,"platform_payment_mode_selected");
                break;

            case "platform_payment_address_update":
                $payload["cart_details"] = $this->getCartDetails();
                if ($this->checkoutSession->getQuote()->getPayment())
                    $payload["payment_mode_details"] = $this->checkoutSession->getQuote()->getPayment()->getData();
                $this->simplApi->event("address_updated",$payload,"platform_payment_address_update");
                break;

            case "platform_payment_initiate":
                $payload["cart_details"] = $this->getCartDetails();
                if ($this->checkoutSession->getQuote()->getPayment())
                    $payload["payment_mode_details"] = $this->checkoutSession->getQuote()->getPayment()->getData();
                $payload["button_text"] = $this->getParam("button_text");
                $this->simplApi->event("payment_initiated",$payload,"platform_payment_initiate");
                break;

            case "platform_order_confirm":
                $payload["order_details"] = $this->getOrderDetails();
                $this->simplApi->event("order_confirmed",$payload,"platform_order_confirm");
                break;

            case "platform_thankyou_pageview":
                $payload["order_details"] = $this->getOrderDetails();
                $this->simplApi->event("order_success",$payload,"platform_thankyou_pageview");
                break;
        }

        $data["status"] = true;
        $resultJson = $this->jsonFactory->create();
        $resultJson->setData($data);
        return $resultJson;
    }

    /**
     * Function to get user details from session
     * @return array
     */
    private function getUserDetails() {

        $userDetails["is_logged_in"] = false;
        if ($this->customerSession->isLoggedIn()) {

            $userDetails = $this->customerSession->getCustomer()->getData();
            $userDetails["is_logged_in"] = true;
        }

        return $userDetails;
    }

    /**
     * For preparing fingerprint data
     * @return array
     */
    private function getFingerprint() {

        if (isset($_SERVER["HTTP_USER_AGENT"]))
            $data["fingerprint"]["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
        if (isset($_SERVER["REMOTE_ADDR"]))
            $data["fingerprint"]["user_ip"] = $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER["HTTP_HOST"]) and isset($_SERVER["REQUEST_URI"]))
            $data["page_url"] = $this->getParam("page_url");

        return $data;
    }

    /**
     * To get cart details from session
     * @return array
     */
    private function getCartDetails()
    {

        $cartDetails = [];
        try {
            $cartDetails = $this->checkoutSession->getQuote()->getData();
            $itemsData = [];
            $items = $this->checkoutSession->getQuote()->getAllItems();
            foreach ($items as $item) {
                $itemsData[] = $item->getData();
            }
            $cartDetails["items"] = $itemsData;

        } catch (\Exception $e) {

            $this->logger->error($e->getMessage());
        }

        return $cartDetails;
    }

    /**
     * @return array
     */
    private function getOrderDetails()
    {

        $orderDetails = [];

        try {
            $orderId = $this->getParam("order_id");
            $order = $this->order->loadByIncrementId($orderId);
            $orderDetails = $order->getData();
            $itemsData = [];
            $items = $order->getAllItems();
            foreach ($items as $item) {
                $itemsData[] = $item->getData();
            }
            $orderDetails["items"] = $itemsData;
        } catch (\Exception $e) {

            $this->logger->error($e->getMessage());
        }

        return $orderDetails;
    }

    /**
     * @param $key
     */
    private function getParam($key) {
        $content = $this->json->unserialize($this->request->getContent());
        if (isset($content[$key])) {
            return $content[$key];
        }
        return null;
    }
}
