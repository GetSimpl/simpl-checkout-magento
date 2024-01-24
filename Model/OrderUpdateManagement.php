<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Simpl\Checkout\Api\Data\Order\AppliedChargesDataInterface;
use Simpl\Checkout\Api\Data\Order\AppliedDiscountsDataInterface;
use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;
use Simpl\Checkout\Api\Data\Order\TransactionDataInterface;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Api\OrderUpdateManagementInterface;
use Simpl\Checkout\Api\Data\OrderDataInterface;
use Simpl\Checkout\Api\Data\CreditMemoDataInterface;
use Simpl\Checkout\Model\Data\Order\Response as OrderResponse;


class OrderUpdateManagement implements OrderUpdateManagementInterface {

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
     * @var OrderResponse
     */
    protected $orderResponse;

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
        OrderResponse                    $orderResponse
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
        $this->orderResponse = $orderResponse;
    }

    /**
     * @inheritDoc
     */
    public function confirm($orderId, $payment, $transaction, $appliedCharges, $appliedDiscounts) {

        try {
            $order = $this->loadOrderById($orderId);
        }catch (\Exception $e) {

            return $this->orderResponse->setError($e->getCode(), $e->getMessage());
        }

        $payment = $order->getPayment();
        if ($payment && $payment->getLastTransId()) {

            return $this->orderResponse->setError("1", "Order already updated");
        }

        if (!$this->simplApi->validatePayment($order, $payment, $transaction)) {

            return $this->orderResponse->setError("2", "Order validation failed");
        }

        $order = $this->applyCharges($order, $appliedCharges);
        $order = $this->applyDiscount($order, $appliedDiscounts);
        try {
            $this->createTransaction($order, $payment, $transaction);
        } catch (\Exception $e) {

            return $this->orderResponse->setError($e->getCode(), $e->getMessage());
        }

        $redirectUrl = $this->simplApi->getRedirectUrl(["order_id" => $orderId]);
        return $this->orderResponse->setUrl($redirectUrl);
    }

    /**
     * @param $order
     * @param AppliedChargesDataInterface[] $appliedCharges
     */
    private function applyCharges($order, $appliedCharges) {

        $amount = 0;
        $comment = '';
        foreach ($appliedCharges as $appliedCharge) {
            $amount += $appliedCharge->getChargesAmountInPaise();
            $comment .= 'Simpl Checkout ' . $appliedCharge->getTitle() .
                ' charge added with description ' .  $appliedCharge->getDescription() .
                ' and amount added is ' . $appliedCharge->getChargesAmountInPaise();
        }
        if ($amount) {
            $order->setData('simpl_applied_charges', $amount);
            $order->addStatusHistoryComment($comment);
        }
        return $order;
    }

    /**
     * @param $order
     * @param AppliedDiscountsDataInterface[] $appliedDiscounts
     */
    private function applyDiscount($order, $appliedDiscounts) {

        $amount = 0;
        $comment = '';
        foreach ($appliedDiscounts as $appliedDiscount) {
            $amount += $appliedDiscount->getDiscountAmountInPaise();
            $comment .= 'Simpl Checkout ' . $appliedDiscount->getTitle() .
                ' discount added with description ' .  $appliedDiscount->getDescription() .
                ' and amount discounted is ' . $appliedDiscount->getDiscountAmountInPaise();
        }
        if ($amount) {
            $order->setData('simpl_applied_discounts', -$amount);
            $order->addStatusHistoryComment($comment);
        }
        return $order;
    }

    /**
     * @param $order
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @return int
     * @throws \Exception
     */
    public function createTransaction($order, $paymentData, $transactionData) {
        return $this->processTransaction($order, $paymentData, $transactionData);
    }

    /**
     * @param $order
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @return int
     * @throws \Exception
     */
    public function updateTransaction($order, $paymentData, $transactionData) {
        return $this->processTransaction($order, $paymentData, $transactionData, true);
    }

    /**
     * @param $order
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @param false $update
     * @return int
     * @throws \Exception
     */
    private function processTransaction($order, $paymentData, $transactionData, $update = false) {
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
            throw new \Exception('Error while saving transaction');
        }
    }

    /**
     * Load Order by ID
     * @param $orderId
     * @return \Magento\Sales\Model\Order
     * @throws \Exception
     */
    private function loadOrderById($orderId) {
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
    private function loadOrderByIncrementId($incrementId) {
        $orderModel = $this->orderFactory->create();
        $order = $orderModel->loadByIncrementId($incrementId);
        if ($order && $order->getId())
            return  $order;
        throw new \Exception('Error processing the request: id');
    }

}
