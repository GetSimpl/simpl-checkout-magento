<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Simpl\Checkout\Api\Data\TransactionDataInterface;

class OrderUpdateManagement implements \Simpl\Checkout\Api\OrderUpdateManagementInterface
{

    /**
     * @var \Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface
     */
    protected $apiResponseData;

    /**
     * @var \Simpl\Checkout\Api\Data\RedirectionUrlDataInterface
     */
    protected $confirmSuccessData;

    /**
     * @var \Simpl\Checkout\Api\Data\ErrorDataInterface
     */
    protected $errorData;

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $orderFactory;

    /**
     * @var \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface
     */
    protected $transactionBuilder;

    protected $invoiceService;

    protected $invoiceSender;

    protected $invoiceRepository;

    /**
     * @param \Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface $apiResponseData
     * @param \Simpl\Checkout\Api\Data\RedirectionUrlDataInterface $confirmSuccessData
     * @param \Simpl\Checkout\Api\Data\ErrorDataInterface $errorData
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param Order\Payment\Transaction\BuilderInterface $transactionBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param \Magento\Sales\Model\Service\InvoiceService $invoiceService
     * @param Order\Email\Sender\InvoiceSender $invoiceSender
     * @param \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository
     */
    public function __construct(
        \Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface $apiResponseData,
        \Simpl\Checkout\Api\Data\RedirectionUrlDataInterface $confirmSuccessData,
        \Simpl\Checkout\Api\Data\ErrorDataInterface $errorData,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface $transactionBuilder,
        OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Magento\Sales\Model\Order\Email\Sender\InvoiceSender $invoiceSender,
        \Magento\Sales\Api\InvoiceRepositoryInterface $invoiceRepository
    )
    {
        $this->apiResponseData = $apiResponseData;
        $this->confirmSuccessData = $confirmSuccessData;
        $this->errorData = $errorData;
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->transactionBuilder = $transactionBuilder;
        $this->invoiceSender = $invoiceSender;
        $this->invoiceService = $invoiceService;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @inheritDoc
     */
    public function confirm($orderId, $payment, $transaction, $appliedCharges, $appliedDiscounts)
    {
        $this->apiResponseData->setSuccess(true);

        try {
            $order = $this->loadOrderById($orderId);
        }catch (\Exception $e) {
            $this->errorData->setCode("1001");
            $this->errorData->setMessage("Invalid Order Details");
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        $payment = $order->getPayment();
        if ($payment->getLastTransId()) {
            $this->errorData->setCode("1002");
            $this->errorData->setMessage("Order already updated");
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        // TODO API Integration for two way hand shake validation

        $this->createTransaction($order, $payment, $transaction);

        $data = 'https://store.com/simpl/order/status?order_id=1234';
        $this->confirmSuccessData->setRedirectionUrl($data);
        $this->apiResponseData->setData($this->confirmSuccessData);
        return $this->apiResponseData;
    }

    /**
     * @param $order
     * @param \Simpl\Checkout\Api\Data\PaymentDataInterface $paymentData
     * @param \Simpl\Checkout\Api\Data\TransactionDataInterface $transactionData
     */
    public function createTransaction($order, $paymentData, $transactionData)
    {
        return $this->processTransaction($order, $paymentData, $transactionData);
    }

    /**
     * @param $order
     * @param \Simpl\Checkout\Api\Data\PaymentDataInterface $paymentData
     * @param \Simpl\Checkout\Api\Data\TransactionDataInterface $transactionData
     */
    public function updateTransaction($order, $paymentData, $transactionData) {
        return $this->processTransaction($order, $paymentData, $transactionData, true);
    }

    /**
     * @param $order
     * @param \Simpl\Checkout\Api\Data\PaymentDataInterface $paymentData
     * @param \Simpl\Checkout\Api\Data\TransactionDataInterface $transactionData
     */
    public function processTransaction($order, $paymentData, $transactionData, $update = false)
    {
        $canProcessInvoice = false;
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

            if ($update) {
                $message = __('Amount %1 refunded via Simpl Checkout.', $formatedPrice);
            } else {
                $message = __('Amount %1 paid via Simpl Checkout.', $formatedPrice);
            }
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
            if ($update) {
                $transaction = $trans->setPayment($payment)
                    ->setOrder($order)
                    ->setTransactionId($transactionData->getId())
                    ->setAdditionalInformation($transData)
                    ->setFailSafe(true)
                    ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_REFUND);
            } else {
                $transaction = $trans->setPayment($payment)
                    ->setOrder($order)
                    ->setTransactionId($transactionData->getId())
                    ->setAdditionalInformation($transData)
                    ->setFailSafe(true)
                    ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_CAPTURE);
            }

            $payment->addTransactionCommentsToOrder(
                $transaction,
                $message
            );
            if ($lastTransId) {
                $payment->setParentTransactionId($lastTransId);
            } else {
                $payment->setParentTransactionId(null);
            }

            if ($update) {
                if ($paymentData->getStatus() == 'refunded') {
                    $order->setState(Order::STATE_CANCELED);
                    $order->setStatus(Order::STATE_CANCELED);
                }
            } else {
                if ($paymentData->getMode() != 'cod') {
                    $order->setState(Order::STATE_PROCESSING);
                    $order->setStatus(Order::STATE_PROCESSING);
                    $canProcessInvoice = true;
                }
            }

            $payment->save();
            $transactionId = $transaction->save()->getTransactionId();

            if ($transactionId and $canProcessInvoice) {
                $this->invoiceOrder($order, $transactionId);
                $order->setTotalPaid($order->getGrandTotal());
            }

            $order->save();

            return  $transactionId;
        } catch (\Exception $e) {
            //log errors here
        }
    }

    /**
     * Load Order by ID
     * @param $orderId
     * @return \Magento\Sales\Model\Order
     * @throws \Exception
     */
    public function loadOrderById($orderId)
    {
        $order = $this->orderFactory->create()->load($orderId);
        if ($order && $order->getId())
            return  $order;
        throw new \Exception('Error processing the request: id');
    }

    /**
     * Load order by $incrementId
     * @param $incrementId
     * @return \Magento\Sales\Model\Order|void|null
     * @throws \Exception
     */
    public function loadOrderByIncrementId($incrementId)
    {
        $orderModel = $this->orderFactory->create();
        $order = $orderModel->loadByIncrementId($incrementId);
        if ($order && $order->getId())
            return  $order;
        throw new \Exception('Error processing the request: id');
    }

    /**
     * @inheritDoc
     */
    public function update($orderId, $payment, $transaction)
    {
        $this->apiResponseData->setSuccess(true);

        try {
            $order = $this->loadOrderById($orderId);
        }catch (\Exception $e) {
            $this->errorData->setCode("1001");
            $this->errorData->setMessage("Invalid Order Details");
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        $this->updateTransaction($order, $payment, $transaction);
        // TODO API Integration for two way hand shake validation

        return $this->apiResponseData;
    }

    public function invoiceOrder($order, $transactionId = null,)
    {
        if ($order->canInvoice())
        {
            try {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);

                if ($transactionId)
                {
                    $invoice->setTransactionId($transactionId);
                }
                $invoice->register();
                $this->invoiceRepository->save($invoice);
                $this->invoiceSender->send($invoice);

                return true;
            } catch (\Exception $e) {
                // TODO Log error
            }
        }
        return null;
    }
}
