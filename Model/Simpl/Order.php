<?php

namespace Simpl\Checkout\Model\Simpl;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\UrlInterface;
use Simpl\Checkout\Logger\Logger;

class Order
{

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
     * @var Logger
     */
    protected $logger;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param CheckoutSession $checkoutSession
     * @param OrderFactory $order
     * @param UrlInterface $url
     * @param Logger $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CheckoutSession $checkoutSession,
        OrderFactory $order,
        UrlInterface $url,
        Logger $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->order = $order;
        $this->url = $url;
        $this->logger = $logger;
    }

    /**
     * Function to init payment, called from frontend.
     * @return array
     */
    public function getCurrentOrder()
    {
        $order = $this->checkoutSession->getLastRealOrder();
        return $this->getOrder($order);
    }


    /**
     * To get order data as per the payment initiate api contract
     * @param \Magento\Sales\Model\Order $order
     * @return array|null
     * @throws \Exception
     */
    public function getOrder($order)
    {
        try {
            $data = $order->getData();
            $data["checkout_url"] = $this->url->getUrl(
                'simpl/cart/restore',
                ['id' => $order->getQuoteId()]
            );
            $this->logger->info('ORDER QUOTE ID: ' . $order->getQuoteId());
            $this->logger->info('checkout_url: ' . $this->url->getUrl(
                'simpl/cart/restore',
                ['id' => $order->getQuoteId()]
            ));

            if ($order->getIsNotVirtual()) {
                $data["shipping_address"] = $order->getShippingAddress()->getData();
            } else {
                $data["shipping_address"] = $order->getBillingAddress()->getData();
            }

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
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getOrderDetails()
    {

        $orderDetails = [];
        $orderId = $this->checkoutSession->getLastRealOrder()->getIncrementId();
        $order = $this->order->loadByIncrementId($orderId);
        $orderDetails = $order->getData();
        $itemsData = [];
        $items = $order->getAllItems();
        foreach ($items as $item) {
            $itemsData[] = $item->getData();
        }
        $orderDetails["items"] = $itemsData;
        $orderDetails["payment_mode_details"] = $order->getPayment()->getData();

        return $orderDetails;
    }
}
