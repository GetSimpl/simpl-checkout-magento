<?php

namespace Simpl\Checkout\Model\Simpl;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\UrlInterface;

class Order {

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var OrderFactory
     */
    protected $order;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param CheckoutSession $checkoutSession
     * @param OrderFactory $order
     * @param UrlInterface $url
     * @param \Magento\Framework\Api\ExtensibleDataObjectConverter $dataObjectConverter
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CheckoutSession $checkoutSession,
        OrderFactory $order,
        UrlInterface $url
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->order = $order;
        $this->url = $url;
    }

    /**
     * Function to init payment, called from frontend.
     * @return array
     */
    public function getCurrentOrder() {
        $order = $this->checkoutSession->getLastRealOrder();
        return $this->getOrder($order);
    }


    /**
     * To get order data as per the payment initiate api contract
     * @param \Magento\Sales\Model\Order $order
     * @return array|null
     * @throws \Exception
     */
    public function getOrder($order) {
        $data = $order->getData();
        $data["checkout_url"] = $this->url->getCurrentUrl();
        $data["shipping_address"] = $order->getShippingAddress()->getData();
        $data["billing_address"] = $order->getBillingAddress()->getData();

        $orderItems = $order->getAllItems();
        $items = [];
        foreach ($orderItems as $orderItem) {
            $orderItemArray = $orderItem->getData();
            $items[] = $orderItemArray;
        }

        $data["items"] = $items;

        $data["figerprint"] = [
            "user_ip" => $order->getRemoteIp(),
            "user_agent" => $_SERVER['HTTP_USER_AGENT']
        ];

        return $data;
    }
}
