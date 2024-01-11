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

class SimplCheckout  extends \Magento\Payment\Model\Method\Adapter
{

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
    )
    {
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
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        if (!$this->simplConfig->isEnabled())
            return false;

        return parent::isAvailable($quote);
    }

    public function capture(InfoInterface $payment, $amount)
    {
        return $this;
    }

    public function refund(InfoInterface $payment, $amount)
    {
        $this->cancel($payment, $amount);

        return $this;
    }

    public function void(InfoInterface $payment)
    {
        $this->cancel($payment);

        return $this;
    }

    public function cancel(InfoInterface $payment, $amount = null)
    {
        try {

            $order = $payment->getOrder();
            if (!is_numeric($amount)) $amount = 0;
            $amount = round((float)$amount, 2);

            $order->setTotalRefunded($order->getTotalRefunded() + $amount);
            $order->setBaseTotalRefunded($order->getBaseTotalRefunded() + $amount);
            $order->setState(Order::STATE_CLOSED);
            $order->setStatus(Order::STATE_CLOSED);

            // TODO API to init refund

        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Refund can not be processed'));
        }

        return $this;
    }

}

