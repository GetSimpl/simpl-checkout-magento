<?php
/**
 * Copyright © Simpl Checkout All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Simpl\Checkout\Model\Payment;

use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Payment\Gateway\Command\CommandManagerInterface;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Config\ValueHandlerPoolInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectFactory;
use Magento\Payment\Gateway\Validator\ValidatorPoolInterface;
use Magento\Payment\Model\InfoInterface;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;
use Simpl\Checkout\Helper\Config as SimplConfig;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Api\Data\CartInterface;

class SimplCheckout  extends Adapter {

    protected $_code = "simplcheckout";

    protected $simplConfig;

    public function __construct(
        ManagerInterface $eventManager,
        ValueHandlerPoolInterface $valueHandlerPool,
        PaymentDataObjectFactory $paymentDataObjectFactory,
        $code,
        $formBlockType,
        $infoBlockType,
        SimplConfig $simplConfig,
        CommandPoolInterface $commandPool = null,
        ValidatorPoolInterface $validatorPool = null,
        CommandManagerInterface $commandExecutor = null,
        LoggerInterface $logger = null
    ) {
        $this->simplConfig = $simplConfig;

        parent::__construct(
            $eventManager,
            $valueHandlerPool,
            $paymentDataObjectFactory,
            $code,
            $formBlockType,
            $infoBlockType,
            $commandPool,
            $validatorPool,
            $commandExecutor,
            $logger
        );
    }

    public function isAvailable(
        CartInterface $quote = null
    ) {
        if (!$this->simplConfig->isEnabled())
            return false;

        return parent::isAvailable($quote);
    }

    public function capture(InfoInterface $payment, $amount) {
        return $this;
    }

    public function refund(InfoInterface $payment, $amount) {
        $this->initRefund($payment, $amount);

        return $this;
    }

    public function void(InfoInterface $payment) {
        $this->cancel($payment);

        return $this;
    }

    public function initRefund(InfoInterface $payment, $amount = null) {
        try {

            $order = $payment->getOrder();
            $creditmemo = $payment->getCreditmemo();
            $orderId = $order->getIncrementId();

            // Refund API request data
            $data["order_id"] = $orderId;
            $data["currency"] = $order->getBaseCurrencyCode();
            $data["credit_memo"]["id"] = $creditmemo->getId();
            $data["credit_memo"]["amount"] = $amount;
            $data["credit_memo"]["status"] = "pending";

            // API to init refund
            if(!$this->simplApi->initRefund($orderId, $data)) {
                throw new Exception(__('Error in API call'));
            }

        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Refund can not be processed'));
        }

        return $this;
    }

    public function cancel(InfoInterface $payment, $amount = null) {
        try {
            $this->initRefund($payment, $amount);
            $order = $payment->getOrder();
            $order->setState(Order::STATE_CLOSED);
            $order->setStatus(Order::STATE_CLOSED);

        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Refund can not be processed'));
        }

        return $this;
    }

}

