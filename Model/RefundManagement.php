<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\Data\CreditmemoCommentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\CreditmemoRepository;
use Magento\Sales\Api\TransactionRepositoryInterface;
use Magento\Sales\Api\Data\TransactionSearchResultInterfaceFactory;
use Simpl\Checkout\Model\Data\RefundConfirmResponse;
use Simpl\Checkout\Api\RefundManagementInterface;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Helper\Alert;

class RefundManagement implements RefundManagementInterface
{

    protected $refundConfirmResponse;

    protected $orderRepository;

    protected $creditmemoRepository;

    protected $creditmemoComment;

    protected $simplApi;

    protected $alert;

    protected $transactionRepository;

    protected $transactionSearchResultInterfaceFactory;

    /**
     * @param RefundConfirmResponse $refundConfirmResponse
     * @param OrderRepositoryInterface $orderRepository
     * @param CreditmemoRepository $creditmemoRepository
     * @param CreditmemoCommentInterface $creditmemoComment
     * @param SimplApi $simplApi
     * @param Alert $alert
     * @param TransactionRepositoryInterface $transactionRepository
     * @param TransactionSearchResultInterfaceFactory $transactionSearchResultInterfaceFactory
     */
    public function __construct(
        RefundConfirmResponse $refundConfirmResponse,
        OrderRepositoryInterface    $orderRepository,
        CreditmemoRepository    $creditmemoRepository,
        CreditmemoCommentInterface  $creditmemoComment,
        SimplApi    $simplApi,
        Alert $alert,
        TransactionRepositoryInterface $transactionRepository,
        TransactionSearchResultInterfaceFactory $transactionSearchResultInterfaceFactory
    ) {
        $this->refundConfirmResponse = $refundConfirmResponse;
        $this->orderRepository = $orderRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->creditmemoComment = $creditmemoComment;
        $this->simplApi = $simplApi;
        $this->alert = $alert;
        $this->transactionRepository = $transactionRepository;
        $this->transactionSearchResultInterfaceFactory = $transactionSearchResultInterfaceFactory;
    }

    /**
     * @inheritDoc
     */
    public function confirm($creditMemoId, $orderId, $transactionId, $status)
    {

        try {
            $creditMemo =  $this->creditmemoRepository->get($creditMemoId);

            if ($creditMemo->getOrderId() != $orderId) {
                throw new \Exception('invalid credit memo');
            }

            if (!$this->simplApi->validateRefund($creditMemoId, $orderId, $transactionId, $status)) {
                 return $this->refundConfirmResponse->setError('validation_failed', 'Validation failed');
            }

            if ($status == "REFUND_SUCCESS") {
                $creditMemo->setState(Creditmemo::STATE_REFUNDED);
                $message = __('Refund processed successfully with transaction id %1', $transactionId);
            } else {
                $creditMemo->setState(Creditmemo::STATE_OPEN);
                $message = __('Transaction id %1 updated with status %2.', $transactionId, $status);
            }

            // Update in order level
            $order = $this->orderRepository->get($orderId);
            $order->getPayment()->setLastTransId($transactionId);

            // Update in transaction level
            $orderTransactions = $this->getOrderTransactions($orderId);
            foreach ($orderTransactions as $orderTransaction) {
                if ($orderTransaction->getTxnId() == $creditMemo->getTransactionId()) {
                    $orderTransaction->setTxnId($transactionId);
                    $orderTransaction->setIsClosed(1);
                    $this->transactionRepository->save($orderTransaction);
                }
            }

            // Update in creditmemo level
            $creditMemo->setTransactionId($transactionId);
            $this->creditmemoComment->setComment($message);
            $this->creditmemoComment->setIsCustomerNotified(1);
            $this->creditmemoComment->setIsVisibleOnFront(1);
            $this->creditmemoComment->setParentId($creditMemo->getEntityId());
            $creditMemo->setComments([$this->creditmemoComment]);
            $this->creditmemoRepository->save($creditMemo);
            $this->orderRepository->save($order);
        } catch (\Exception $e) {
            $this->alert->alert($e->getMessage(), 'ERROR', $e->getTraceAsString());
            return $this->refundConfirmResponse->setError('error_request', $e->getMessage());
        }

        return $this->refundConfirmResponse->setMessage("refund confirm successfully");
    }

    private function getOrderTransactions($orderId)
    {
        $transactions = $this->transactionSearchResultInterfaceFactory->create()->addOrderIdFilter($orderId);
        return $transactions->getItems();
    }
}
