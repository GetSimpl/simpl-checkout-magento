<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Simpl\Checkout\Api\Data\ApiResponseDataInterface;
use Simpl\Checkout\Api\Data\AppliedChargesDataInterface;
use Simpl\Checkout\Api\Data\AppliedDiscountsDataInterface;
use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface;
use Simpl\Checkout\Api\Data\PaymentDataInterface;
use Simpl\Checkout\Api\Data\RedirectionUrlDataInterface;
use Simpl\Checkout\Api\Data\TransactionDataInterface;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Api\OrderUpdateManagementInterface;


class OrderUpdateManagement implements OrderUpdateManagementInterface {

    /**
     * @var OrderConfirmSuccessDataInterface
     */
    protected $apiResponseData;

    /**
     * @var RedirectionUrlDataInterface
     */
    protected $confirmSuccessData;

    /**
     * @var ErrorDataInterface
     */
    protected $errorData;

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

    protected $invoiceService;

    protected $invoiceSender;

    protected $invoiceRepository;

    protected $response;

    protected $simplApi;

    private $order;

    /**
     * @param OrderConfirmSuccessDataInterface $apiResponseData
     * @param RedirectionUrlDataInterface $confirmSuccessData
     * @param ErrorDataInterface $errorData
     * @param OrderFactory $orderFactory
     * @param BuilderInterface $transactionBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceService $invoiceService
     * @param InvoiceSender $invoiceSender
     * @param InvoiceRepositoryInterface $invoiceRepository
     * @param ApiResponseDataInterface $response
     * @param SimplApi $simplApi
     */
    public function __construct(
        OrderConfirmSuccessDataInterface $apiResponseData,
        RedirectionUrlDataInterface      $confirmSuccessData,
        ErrorDataInterface               $errorData,
        OrderFactory                     $orderFactory,
        BuilderInterface                 $transactionBuilder,
        OrderRepositoryInterface         $orderRepository,
        InvoiceService                   $invoiceService,
        InvoiceSender                    $invoiceSender,
        InvoiceRepositoryInterface       $invoiceRepository,
        CreditmemoRepositoryInterface    $creditmemoRepository,
        ApiResponseDataInterface         $response,
        SimplApi                         $simplApi,
        OrderDataInterface               $orderData,
        CreditMemoDataInterface          $creditMemoData
    ) {
        $this->apiResponseData = $apiResponseData;
        $this->confirmSuccessData = $confirmSuccessData;
        $this->errorData = $errorData;
        $this->orderRepository = $orderRepository;
        $this->orderFactory = $orderFactory;
        $this->transactionBuilder = $transactionBuilder;
        $this->invoiceSender = $invoiceSender;
        $this->invoiceService = $invoiceService;
        $this->invoiceRepository = $invoiceRepository;
        $this->response = $response;
        $this->errorData = $errorData;
        $this->simplApi = $simplApi;
    }

    /**
     * @inheritDoc
     */
    public function confirm($orderId, $payment, $transaction, $appliedCharges, $appliedDiscounts) {
        $this->apiResponseData->setSuccess(false);

        try {
            $this->order = $this->loadOrderById($orderId);
        }catch (\Exception $e) {
            $this->errorData->setCode($e->getCode());
            $this->errorData->setMessage($e->getMessage());
            $this->response->setError($this->errorData);
            return $this->apiResponseData;
        }

        $payment = $this->order->getPayment();
        if ($payment->getLastTransId()) {
            $this->errorData->setCode("1");
            $this->errorData->setMessage("Order already updated");
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        if (!$this->simplApi->validatePayment($this->order, $payment, $transaction)) {
            $this->errorData->setCode("2");
            $this->errorData->setMessage("Order validation failed");
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        $this->applyCharges($appliedCharges);
        $this->applyDiscount($appliedDiscounts);
        try {
            $this->createTransaction($payment, $transaction);
        } catch (\Exception $e) {

            $this->errorData->setCode("3");
            $this->errorData->setMessage($e->getMessage());
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        $redirectUrl = $this->simplApi->getRedirectUrl(["order_id" => $orderId]);
        $this->apiResponseData->setSuccess(true);
        $this->confirmSuccessData->setRedirectionUrl($redirectUrl);
        $this->apiResponseData->setData($this->confirmSuccessData);
        return $this->apiResponseData;
    }

    /**
     * @param AppliedChargesDataInterface[] $appliedCharges
     */
    private function applyCharges($appliedCharges) {

        $amount = 0;
        $comment = '';
        foreach ($appliedCharges as $appliedCharge) {
            $amount += $appliedCharge->getChargesAmountInPaise();
            $comment .= 'Simpl Checkout ' . $appliedCharge->getTitle() .
                ' charge added with description ' .  $appliedCharge->getDescription() .
                ' and amount added is ' . $appliedCharge->getChargesAmountInPaise();
        }
        if ($amount) {
            $this->order->setData('simpl_applied_charges', $amount);
            $this->order->addStatusHistoryComment($comment);
        }
    }

    /**
     * @param AppliedDiscountsDataInterface[] $appliedDiscounts
     */
    private function applyDiscount($appliedDiscounts) {

        $amount = 0;
        $comment = '';
        foreach ($appliedDiscounts as $appliedDiscount) {
            $amount += $appliedDiscount->getDiscountAmountInPaise();
            $comment .= 'Simpl Checkout ' . $appliedDiscount->getTitle() .
                ' discount added with description ' .  $appliedDiscount->getDescription() .
                ' and amount discounted is ' . $appliedDiscount->getDiscountAmountInPaise();
        }
        if ($amount) {
            $this->order->setData('simpl_applied_discounts', -$amount);
            $this->order->addStatusHistoryComment($comment);
        }
    }

    /**
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @return int
     * @throws \Exception
     */
    public function createTransaction($paymentData, $transactionData) {
        return $this->processTransaction($paymentData, $transactionData);
    }

    /**
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @return int
     * @throws \Exception
     */
    public function updateTransaction($paymentData, $transactionData) {
        return $this->processTransaction($paymentData, $transactionData, true);
    }

    /**
     * @param PaymentDataInterface $paymentData
     * @param TransactionDataInterface $transactionData
     * @param false $update
     * @return int
     * @throws \Exception
     */
    private function processTransaction($paymentData, $transactionData, $update = false) {
        $canProcessInvoice = false;
        try {
            //get payment object from order object
            $payment = $this->order->getPayment();
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
            $formatedPrice = $this->order->getBaseCurrency()->formatTxt(
                $this->order->getGrandTotal()
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
                    ->setOrder($this->order)
                    ->setTransactionId($transactionData->getId())
                    ->setAdditionalInformation($transData)
                    ->setFailSafe(true)
                    ->build(\Magento\Sales\Model\Order\Payment\Transaction::TYPE_REFUND);
            } else {
                $transaction = $trans->setPayment($payment)
                    ->setOrder($this->order)
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
                    $this->order->setState(Order::STATE_CANCELED);
                    $this->order->setStatus(Order::STATE_CANCELED);
                }
            } else {
                if ($paymentData->getMode() != 'cod') {
                    $this->order->setState(Order::STATE_PROCESSING);
                    $this->order->setStatus(Order::STATE_PROCESSING);
                    $canProcessInvoice = true;
                }
            }

            $payment->save();
            $transactionId = $transaction->save()->getTransactionId();

            if ($transactionId and $canProcessInvoice) {
                $this->invoiceOrder($this->order, $transactionId);
                $this->order->setTotalPaid($this->order->getGrandTotal());
            }

            $this->order->save();

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

    private function invoiceOrder($order, $transactionId = null) {
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
                throw new \Exception('Error processing invoice');
            }
        }
        return null;
    }
}
