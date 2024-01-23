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
        $orderId = $this->checkoutSession->getLastRealOrder();
        return $this->getOrder($orderId);
    }


    /**
     * To get order data as per the payment initiate api contract
     * @param $id
     * @return array|null
     * @throws \Exception
     */
    public function getOrder($id) {
        $data = null;
        try {
            $order = $this->orderRepository->get($id);
        }catch (\Exception $e) {
            throw new \Exception('Error getting order');
        }
        $data["order_id"] = $id;
        $data["checkout_url"] = $this->url->getCurrentUrl();
        $data["increment_id"] = $order->getIncrementId();
        $data["customer"] = [
            "firstname" => $order->getCustomerFirstname(),
            "lastname" => $order->getCustomerLastname(),
            "mobile" => $order->getBillingAddress()->getTelephone(),
            "email" => $order->getCustomerEmail()
        ];
        $data["shipping_address"] = $order->getShippingAddress()->getData();
        $data["billing_address"] = $order->getBillingAddress()->getData();

        $data["totals"] = $this->getTotals($order);
        $data["total_segments"] = $this->getTotalsSegments($order);

        $data["shipping_method_code"] = $order->getShippingMethod();
        $data["shipping_carrier_code"] = $order->getShippingMethod();

        $data["figerprint"] = [
            "user_ip" => $order->getRemoteIp(),
            "user_agent" => $_SERVER['HTTP_USER_AGENT']
        ];

        return $data;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return array
     */
    private function getTotalsSegments($order) {

        $totals = [];
        $orderNew = $this->order->create()->loadByIncrementId($order->getIncrementId());
        $totalsSegments = $orderNew->getTotals();

        if ($totalsSegments) {
            foreach ($totalsSegments as $totalSegment) {
                $totals[]['code'] = $totalSegment->getCode();
                $totals[]['title'] = $totalSegment->getTitle();
                $totals[]['value'] = $totalSegment->getValue();
            }
        }

        return $totals;
    }

    /**
     * @param \Magento\Sales\Api\Data\OrderInterface $order
     * @return array
     */
    private function getTotals($order) {

        $orderItems = $order->getAllItems();
        $items = [];

        foreach ($orderItems as $orderItem) {

            $orderItemArray = $orderItem->getData();
            $items[] = $orderItemArray;
        }

        $data = [
            "grand_total" => $order->getGrandTotal(),
            "base_grand_total" => $order->getBaseGrandTotal(),
            "subtotal" => $order->getSubtotal(),
            "base_subtotal" => $order->getBaseSubtotal(),
            "discount_amount" => $order->getDiscountAmount(),
            "base_discount_amount" => $order->getBaseDiscountAmount(),
            "shipping_amount" => $order->getShippingAmount(),
            "base_shipping_amount" => $order->getBaseShippingAmount(),
            "shipping_discount_amount" => $order->getShippingDiscountAmount(),
            "base_shipping_discount_amount" => $order->getBaseShippingDiscountAmount(),
            "tax_amount" => $order->getTaxAmount(),
            "base_tax_amount" => $order->getBaseTaxAmount(),
            "shipping_tax_amount" => $order->getShippingTaxAmount(),
            "base_shipping_tax_amount" => $order->getBaseShippingTaxAmount(),
            "subtotal_incl_tax" => $order->getSubtotalInclTax(),
            "shipping_incl_tax" => $order->getShippingInclTax(),
            "base_shipping_incl_tax" => $order->getBaseShippingInclTax(),
            "base_currency_code" => $order->getBaseCurrencyCode(),
            "items_qty" => $order->getTotalQtyOrdered(),
            "items" => $items
        ];
        return $data;
    }
}
