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
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Psr\Log\LoggerInterface;
use Simpl\Checkout\Helper\Config as SimplConfig;
use Magento\Payment\Model\Method\Adapter;
use Magento\Quote\Api\Data\CartInterface;
use Simpl\Checkout\Helper\SimplApi;
use Magento\Sales\Model\Order\CreditmemoRepository;

class SimplCheckout extends Adapter
{

    protected $_code = "simplcheckout";

    protected $simplConfig;

    protected $simplApi;

    protected $creditmemoRepository;

    protected $_isVirtual;

    public function __construct(
        ManagerInterface $eventManager,
        ValueHandlerPoolInterface $valueHandlerPool,
        PaymentDataObjectFactory $paymentDataObjectFactory,
        $code,
        $formBlockType,
        $infoBlockType,
        SimplConfig $simplConfig,
        SimplApi $simplApi,
        CreditmemoRepository $creditmemoRepository,
        CommandPoolInterface $commandPool = null,
        ValidatorPoolInterface $validatorPool = null,
        CommandManagerInterface $commandExecutor = null,
        LoggerInterface $logger = null
    ) {
        $this->simplConfig = $simplConfig;
        $this->simplApi = $simplApi;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->_isVirtual = false;

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
        if (!$this->simplConfig->isEnabled()) {
            return false;
        }

        $this->validateQuote($quote);

        if ($this->_isVirtual and !$this->simplConfig->isVirtualProductEnabled()) {
            return false;
        }

        // Hide Simpl Checkout if quantity in cart is in decimals
        $totalQuantity = $quote->getItemsQty();
        if(abs($totalQuantity - (int)$totalQuantity) > 0.0001) {
            return false;
        }

        // Hide Simpl checkout if shipping is outside India
        $countryCode = $quote->getShippingAddress()->getData('country_id');
        if($countryCode != 'IN') {
            return false;   
        }

        if ($this->simplConfig->getAllowedEmails()) {

            $emailsIds = $this->simplConfig->getAllowedEmails();
            $emailsIds = rtrim($emailsIds, ',');
            $emailsIds = str_replace(' ', '', $emailsIds);
            $emails = explode(',', $emailsIds);
            $customerEmail = $quote->getCustomerEmail();
            if (in_array($customerEmail, $emails, true)) {
                return true;
            }
            return false;
        }

        return parent::isAvailable($quote);
    }

    /**
     * @param $quote
     * @return bool
     */
    private function validateQuote($quote)
    {

        if ($quote->getIsVirtual()) {
            $this->_isVirtual = true;
        }

        $items = $quote->getAllItems();
        foreach ($items as $item) {
            if ($item->getIsVirtual()) {
                $this->_isVirtual = true;
            }
        }
    }

    public function capture(InfoInterface $payment, $amount)
    {
        return $this;
    }

    public function refund(InfoInterface $payment, $amount)
    {
        $this->initRefund($payment, $amount);
        return $this;
    }

    public function void(InfoInterface $payment)
    {
        $this->cancel($payment);
        return $this;
    }

    public function initRefund(InfoInterface $payment, $amount = null)
    {
        try {
            $order = $payment->getOrder();
            $creditmemo = $payment->getCreditmemo();
            $creditmemo->setState(Creditmemo::STATE_OPEN);
            $creditmemo = $this->creditmemoRepository->save($creditmemo);
            $orderId = $order->getId();
            $payment->setIsTransactionClosed(0);

            // Refund API request data
            $data["order_id"] = $orderId;
            $data["currency"] = $order->getBaseCurrencyCode();
            $data["credit_memo"]["id"] = $creditmemo->getId();
            $data["credit_memo"]["amount"] = $amount;
            $data["credit_memo"]["status"] = "pending";

            // API to init refund
            if (!$this->simplApi->initRefund($orderId, $data)) {
                throw new \Exception('Error in API call');
            }
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Refund can not be processed'));
        }

        return $this;
    }

    public function cancel(InfoInterface $payment, $amount = null)
    {
       return $this;
    }
}
