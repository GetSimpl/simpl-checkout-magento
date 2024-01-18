<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Simpl\Checkout\Helper\SimplApi as SimplApi;
use Magento\Framework\UrlInterface;

class Simpl
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
     * @var SimplApi
     */
    protected $simpl;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param CheckoutSession $checkoutSession
     * @param OrderFactory $order
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        CheckoutSession $checkoutSession,
        OrderFactory $order,
        SimplApi $simpl,
        UrlInterface $url
    ) {
        $this->orderRepository = $orderRepository;
        $this->checkoutSession = $checkoutSession;
        $this->order = $order;
        $this->simpl = $simpl;
        $this->url = $url;
    }

    /**
     * Function to init payment, called from frontend.
     * @return string|null
     */
    public function init() {
        $url = null;
        $orderId = $this->checkoutSession->getLastRealOrder();
        $data = $this->getOrderInitRequestData($orderId);
        if($data) {
            $url = $this->initPayment($data);
        }
        return $url;
    }

    /**
     * Function Integrate API to init payment.
     * @param $data
     * @return string
     */
    public function initPayment($data) {
        return $this->simpl->initPayment($data);
    }


    /**
     * To get order data as per the payment initiate api contract
     * @param $id
     * @return array|null
     */
    public function getOrderInitRequestData($id) {
        $data = null;
        try {
            $order = $this->orderRepository->get($id);
        }catch (\Exception $e) {
            return $data;
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
     */
    public function getTotals($order) {
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
            "items_qty" => $order->getTotalQtyOrdered()
        ];
        return $data;
    }
}
