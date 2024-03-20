<?php

namespace Simpl\Checkout\Model;

use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Service\InvoiceService;
use Simpl\Checkout\Api\Data\CreditMemoDataInterface;
use Simpl\Checkout\Api\Data\Order\AppliedChargesDataInterface;
use Simpl\Checkout\Api\Data\Order\AppliedDiscountsDataInterface;
use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;
use Simpl\Checkout\Api\Data\Order\TransactionDataInterface;
use Simpl\Checkout\Api\Data\OrderDataInterface;
use Simpl\Checkout\Api\OrderConfirmManagementInterface;
use Simpl\Checkout\Helper\Config;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Logger\Logger;
use Simpl\Checkout\Model\SimplOrderFactory;
use Simpl\Checkout\Model\ResourceModel\SimplOrder as SimplResource;
use Simpl\Checkout\Model\Data\Order\OrderConfirmResponse;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;

class OrderConfirmManagement implements OrderConfirmManagementInterface
{

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var BuilderInterface
     */
    protected $transactionBuilder;
    /**
     * @var InvoiceService
     */
    protected $invoiceService;
    /**
     * @var InvoiceSender
     */
    protected $invoiceSender;
    /**
     * @var InvoiceRepositoryInterface
     */
    protected $invoiceRepository;
    /**
     * @var CreditmemoRepositoryInterface
     */
    protected $creditmemoRepository;
    /**
     * @var SimplApi
     */
    protected $simplApi;
    /**
     * @var OrderDataInterface
     */
    protected $orderData;
    /**
     * @var CreditMemoDataInterface
     */
    protected $creditMemoData;
    /**
     * @var OrderConfirmResponse
     */
    protected $orderConfirmResponse;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var SimplOrderFactory
     */
    protected $simplFactory;
    /**
     * @var SimplResource
     */
    protected $simplResource;
    /**
     * @var OrderSender
     */
    protected $orderEmailSender;

    /**
     * @param OrderFactory $orderFactory
     * @param BuilderInterface $transactionBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceService $invoiceService
     * @param InvoiceSender $invoiceSender
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param CreditmemoRepositoryInterface $creditmemoRepository
     * @param SimplApi $simplApi
     * @param OrderDataInterface $orderData
     * @param CreditMemoDataInterface $creditMemoData
     * @param OrderConfirmResponse $orderConfirmResponse
     * @param Config $config
     * @param Logger $logger
     * @param SimplOrderFactory $simplFactory
     * @param SimplResource $simplResource
     * @param OrderSender $orderEmailSender
     */
    public function __construct(
        OrderFactory                     $orderFactory,
        BuilderInterface                 $transactionBuilder,
        OrderRepositoryInterface         $orderRepository,
        InvoiceService                   $invoiceService,
        InvoiceSender                    $invoiceSender,
        InvoiceRepositoryInterface       $invoiceRepository,
        CreditmemoRepositoryInterface    $creditmemoRepository,
        SimplApi                         $simplApi,
        OrderDataInterface               $orderData,
        CreditMemoDataInterface          $creditMemoData,
        OrderConfirmResponse             $orderConfirmResponse,
        Config                           $config,
        Logger                           $logger,
        SimplOrderFactory                $simplFactory,
        SimplResource                    $simplResource,
        OrderSender                      $orderEmailSender
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->transactionBuilder = $transactionBuilder;
        $this->invoiceSender = $invoiceSender;
        $this->invoiceService = $invoiceService;
        $this->invoiceRepository = $invoiceRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->simplApi = $simplApi;
        $this->orderData = $orderData;
        $this->creditMemoData = $creditMemoData;
        $this->orderConfirmResponse = $orderConfirmResponse;
        $this->config = $config;
        $this->logger = $logger;
        $this->simplFactory = $simplFactory;
        $this->simplResource = $simplResource;
        $this->orderEmailSender = $orderEmailSender;
    }

    /**
     * @inheritDoc
     */
    public function confirm($orderId, $payment, $transaction, $appliedCharges, $appliedDiscounts)
    {
        try {
            $order = $this->loadOrderById($orderId);
        } catch (\Exception $e) {
            $stacktrace = $e->getTraceAsString() ?? null;
            $message    = $e->getMessage();
            $this->logger->error($message, ['stacktrace' => $stacktrace]);
            return $this->orderConfirmResponse->setError($e->getCode(), $e->getMessage());
        }

        $this->logger->info("Order id: " . $orderId);
        $this->logger->info("Payment Grand Total: " . $payment->getGrandTotal());
        $this->logger->info("Payment Total Paid: " . $payment->getTotalPaid());
        $this->logger->info("Payment Mode: " . $payment->getMode());
        $this->logger->info("Payment Status: " . $payment->getStatus());
        $this->logger->info("Payment Method: " . $payment->getMethod());

        $orderPayment = $order->getPayment();
        if ($orderPayment && $orderPayment->getLastTransId()) {
            return $this->orderConfirmResponse->setError("order_confirm_failed", "Order already confirmed");
        }

        if (!$orderPayment || $orderPayment->getMethod() != Config::KEY_PAYMENT_CODE) {
            return $this->orderConfirmResponse->setError("order_confirm_failed", "Not a simpl checkout order");
        }

        if (!$this->simplApi->validatePayment($order, $payment, $transaction)) {
            $message = "Order validation failed";
            $this->logger->error($message);
            return $this->orderConfirmResponse->setError("order_confirm_failed", $message);
        }

        try {
            $this->storeSimplOrderDetails($orderId, $payment, $transaction);

            if ($payment->getStatus() == 'SUCCEEDED') {
                $this->handlePaymentSuccess($order, $payment, $transaction, $appliedCharges, $appliedDiscounts);
            } elseif ($payment->getStatus() == 'FAILED') {
                $order->setState(Order::STATE_CANCELED);
                $order->setStatus(Order::STATE_CANCELED);
                $statusComment = "Payment failed";
                $order->addStatusHistoryComment($statusComment);
                $order->save();
            } else {
                $order->setState(Order::STATUS_FRAUD);
                $order->setStatus(Order::STATUS_FRAUD);
                $statusComment = "Fraud detected";
                $order->addStatusHistoryComment($statusComment);
                $order->save();
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            return $this->orderConfirmResponse->setError($e->getCode(), $e->getMessage());
        }

        $redirectUrl = $this->simplApi->getRedirectUrl(["order_id" => $orderId]);
        return $this->orderConfirmResponse->setRedirectionURL($redirectUrl);
    }

    /**
     * For saving order details to simpl repo
     *
     * @param int|string $orderId
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     * @return void
     * @throws AlreadyExistsException
     */
    private function storeSimplOrderDetails($orderId, $payment, $transaction)
    {

        $simplOrder = $this->simplFactory->create();
        $this->simplResource->load($simplOrder, $orderId, 'order_id');
        if (!$simplOrder->getId()) {
                $simplOrder->setOrderId($orderId);
        }

        $simplOrder->setPaymentStatus($payment->getStatus());
        $simplOrder->setPaymentMethod($payment->getMethod());
        $simplOrder->setTransactionId($transaction->getId());
        $this->simplResource->save($simplOrder);
    }

    /**
     * Update and process order based on payment status
     *
     * @param Order $order
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     * @param AppliedChargesDataInterface $appliedCharges
     * @param AppliedDiscountsDataInterface $appliedDiscounts
     * @return void
     * @throws \Exception
     */
    private function handlePaymentSuccess($order, $payment, $transaction, $appliedCharges, $appliedDiscounts)
    {

        $canProcessInvoice = false;
        $transactionId = null;

        $order = $this->applyCharges($order, $appliedCharges);
        $order = $this->applyDiscount($order, $appliedDiscounts);

        $totalPaid = $payment->getTotalPaid();
        $grandTotal = $payment->getGrandTotal();

        $order->setTotalPaid($totalPaid);
        $order->setBaseTotalPaid($totalPaid);
        $order->setGrandTotal($grandTotal);
        $order->setBaseGrandTotal($grandTotal);

        if ($payment->getMode() != 'COD') {
            $order->setState(Order::STATE_PROCESSING);
            $order->setStatus(Order::STATE_PROCESSING);
            $order->setCustomerNoteNotify(true);
            $order->setCanSendNewEmailFlag(true);
            $canProcessInvoice = true;
            $order->save();
            $transactionId = $this->processTransaction($order, $payment, $transaction);
        } else {
            $newOrderStatus = $this->config->getNewOrderStatus();
            $order->setState(Order::STATE_NEW);
            $order->setStatus($newOrderStatus);
            $order->setCustomerNoteNotify(true);
            $order->setCanSendNewEmailFlag(true);
            $canProcessInvoice = false;
            $order->save();
        }

        $this->orderEmailSender->send($order);

        if ($transactionId && $canProcessInvoice) {
            $this->invoiceOrder($order, $payment, $transactionId);
        }
    }

    /**
     * Save the applied charges with order
     *
     * @param Order $order
     * @param AppliedChargesDataInterface[] $appliedCharges
     */
    private function applyCharges($order, $appliedCharges)
    {
        $amount = 0;
        $comment = '';
        foreach ($appliedCharges as $appliedCharge) {
            $amount += $appliedCharge->getAmount();
            $comment .= 'Simpl Checkout ' . $appliedCharge->getTitle() .
                ' charge added with description ' . $appliedCharge->getDescription() .
                ' and amount added is ' . $appliedCharge->getAmount();
        }
        if ($amount) {
            $order->setData('simpl_applied_charges', $amount);
            $order->addStatusHistoryComment($comment);
        }
        return $order;
    }

    /**
     * Save the applied discounts with order
     *
     * @param Order $order
     * @param AppliedDiscountsDataInterface[] $appliedDiscounts
     */
    private function applyDiscount($order, $appliedDiscounts)
    {
        $amount = 0;
        $comment = '';
        foreach ($appliedDiscounts as $appliedDiscount) {
            $amount += $appliedDiscount->getAmount();
            $comment .= 'Simpl Checkout ' . $appliedDiscount->getTitle() .
                ' discount added with description ' . $appliedDiscount->getDescription() .
                ' and amount discounted is ' . $appliedDiscount->getAmount();
        }
        if ($amount) {
            $order->setData('simpl_applied_discounts', -$amount);
            $order->addStatusHistoryComment($comment);
        }
        return $order;
    }

    /**
     * Save the transaction details with order
     *
     * @param Order $order
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @return int
     * @throws \Exception
     */
    private function processTransaction($order, $paymentData, $transactionData)
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
                ->setAdditionalInformation('transaction_closed', $transactionData->isClosed());
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
            throw new LocalizedException(__('Error while saving transaction'));
        }
    }

    /**
     * Load Order by ID
     *
     * @param int|string $orderId
     * @return Order
     * @throws LocalizedException
     */
    private function loadOrderById($orderId)
    {
        $order = $this->orderFactory->create()->load($orderId);
        if ($order && $order->getId()) {
            return  $order;
        }
        throw new LocalizedException(__('Error processing the request: id'));
    }

    /**
     * Load order by $incrementId
     *
     * @param string|int $incrementId
     * @return Order|void|null
     * @throws \Exception
     */
    private function loadOrderByIncrementId($incrementId)
    {
        $orderModel = $this->orderFactory->create();
        $order = $orderModel->loadByIncrementId($incrementId);
        if ($order && $order->getId()) {
            return  $order;
        }
        throw new LocalizedException(__('Error processing the request: id'));
    }

    /**
     * Generate invoice for order and send invoice mail
     *
     * @param Order $order
     * @param PaymentDataInterface $payment
     * @param int|string|null $transactionId
     * @return true|null
     * @throws \Exception
     */
    private function invoiceOrder($order, $payment, $transactionId = null)
    {
        if ($order->canInvoice()) {
            try {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(Invoice::CAPTURE_ONLINE);

                $totalPaid = $payment->getTotalPaid();
                $grandTotal = $payment->getGrandTotal();

                $invoice->setGrandTotal($grandTotal);
                $invoice->setBaseGrandTotal($grandTotal);

                if ($transactionId) {
                    $invoice->setTransactionId($transactionId);
                }
                $invoice->register();
                $this->invoiceRepository->save($invoice);
                $this->invoiceSender->send($invoice);

                return true;
            } catch (\Exception $e) {
                throw new LocalizedException(__('Error processing invoice'));
            }
        }
        return null;
    }
}
