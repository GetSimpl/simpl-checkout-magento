<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;
use Simpl\Checkout\Api\Data\Order\TransactionDataInterface;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Api\OrderUpdateManagementInterface;
use Simpl\Checkout\Api\Data\OrderDataInterface;
use Simpl\Checkout\Api\Data\CreditMemoDataInterface;
use Simpl\Checkout\Model\Data\Order\OrderUpdateResponse;
use Simpl\Checkout\Helper\Config;
use Simpl\Checkout\Logger\Logger;

class OrderUpdateManagement implements OrderUpdateManagementInterface
{

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var BuilderInterface
     */
    protected $transactionBuilder;

    /**
     * @var OrderUpdateResponse
     */
    protected $orderUpdateResponse;

    /**
     * @var SimplApi
     */
    protected $simplApi;

    protected $logger;

    /**
     * @param OrderFactory $orderFactory
     * @param BuilderInterface $transactionBuilder
     * @param SimplApi $simplApi
     * @param OrderUpdateResponse $orderUpdateResponse
     * @param Logger $logger
     */
    public function __construct(
        OrderFactory                     $orderFactory,
        BuilderInterface                 $transactionBuilder,
        SimplApi                         $simplApi,
        OrderUpdateResponse              $orderUpdateResponse,
        Logger                           $logger
    ) {
        $this->orderFactory = $orderFactory;
        $this->transactionBuilder = $transactionBuilder;
        $this->simplApi = $simplApi;
        $this->orderUpdateResponse = $orderUpdateResponse;
        $this->logger = $logger;
    }

    /**
     * @param $order
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @return int
     * @throws \Exception
     */
    private function updateTransaction($order, $paymentData, $transactionData)
    {
        try {
            //get payment object from order object
            $payment = $order->getPayment();
            $lastTransId = null;
            if ($payment->getLastTransId()) {
                $lastTransId = $payment->getEntityId();
            }
            $payment->setLastTransId($transactionData->getId());
            $payment->setTransactionId($transactionData->getId());
            $payment
                ->setAdditionalInformation('payment_status', $paymentData->getStatus())
                ->setAdditionalInformation('payment_mode', $paymentData->getMode())
                ->setAdditionalInformation('payment_method', $paymentData->getMethod())
                ->setAdditionalInformation('transaction_id', $transactionData->getId())
                ->setAdditionalInformation('transaction_parent_id', $transactionData->getParentId())
                ->setAdditionalInformation('transaction_type', $transactionData->getType())
                ->setAdditionalInformation('transaction_closed', $transactionData->isClosed())
            ;
            $formatedPrice = $order->getBaseCurrency()->formatTxt(
                $order->getGrandTotal()
            );

            $message = __('Amount %1 paid via Simpl Checkout.', $formatedPrice);
            //get the object of builder class
            $trans = $this->transactionBuilder;

            $transData = [
                'payment_status', $paymentData->getStatus(),
                'payment_mode', $paymentData->getMode(),
                'payment_method', $paymentData->getMethod(),
                'transaction_id', $transactionData->getId(),
                'transaction_parent_id', $transactionData->getParentId(),
                'transaction_type', $transactionData->getType(),
                'transaction_closed', $transactionData->isClosed()
            ];

            //build method creates the transaction and returns the object
            $transaction = $trans->setPayment($payment)
                ->setOrder($order)
                ->setTransactionId($transactionData->getId())
                ->setAdditionalInformation($transData)
                ->setFailSafe(true)
                ->build(Transaction::TYPE_CAPTURE);

            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            if ($lastTransId) {
                $payment->setParentTransactionId($lastTransId);
            } else {
                $payment->setParentTransactionId(null);
            }

            $payment->save();
            return $transaction->save()->getTransactionId();
        } catch (\Exception $e) {
            throw new \Exception('Error while saving transaction');
        }
    }

    /**
     * Load Order by ID
     * @param $orderId
     * @return \Magento\Sales\Model\Order
     * @throws \Exception
     */
    private function loadOrderById($orderId)
    {
        $order = $this->orderFactory->create()->load($orderId);
        if ($order && $order->getId()) {
            return  $order;
        }
        throw new \Exception('Error processing the request: id');
    }

    /**
     * @inheritDoc
     */
    public function update($orderId, $payment, $transaction)
    {

        try {
            $order = $this->loadOrderById($orderId);
        } catch (\Exception $e) {
            return $this->orderUpdateResponse->setError($e->getCode(), $e->getMessage());
        }

        $orderPayment = $order->getPayment();
        if (!$orderPayment || $orderPayment->getMethod() != Config::KEY_PAYMENT_CODE) {
            return $this->orderUpdateResponse->setError("order_update_failed", "Not a simpl checkout order");
        }

        if (!$this->simplApi->validatePayment($order, $payment, $transaction)) {
            return $this->orderUpdateResponse->setError("order_update_failed", "Order validation failed");
        } 

        try {
            $this->updateTransaction($order, $payment, $transaction);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(),['stacktrace' => $e->getTraceAsString()]);
        }

        return $this->orderUpdateResponse->setMessage('updated successfully');
    }
}
