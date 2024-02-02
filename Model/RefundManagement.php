<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\Data\CreditmemoCommentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\CreditmemoRepository;
use Simpl\Checkout\Model\Data\RefundConfirmResponse;
use Simpl\Checkout\Api\RefundManagementInterface;
use Simpl\Checkout\Helper\SimplApi;

class RefundManagement implements RefundManagementInterface {

    protected $refundConfirmResponse;

    protected $orderRepository;

    protected $creditmemoRepository;

    protected $creditmemoComment;

    protected $simplApi;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param CreditmemoRepository $creditmemoRepository
     * @param CreditmemoCommentInterface $creditmemoComment
     * @param SimplApi $simplApi
     */
    public function __construct(
        RefundConfirmResponse $refundConfirmResponse,
        OrderRepositoryInterface    $orderRepository,
        CreditmemoRepository    $creditmemoRepository,
        CreditmemoCommentInterface  $creditmemoComment,
        SimplApi    $simplApi
    ) {
        $this->refundConfirmResponse = $refundConfirmResponse;
        $this->orderRepository = $orderRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->creditmemoComment = $creditmemoComment;
        $this->simplApi = $simplApi;
    }

    /**
     * @inheritDoc
     */
    public function confirm($creditMemoId, $orderId, $transactionId, $status) {

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
                $message = __('Refund processed successfully with transaction id %1',$transactionId);
            } else {
                $creditMemo->setState(Creditmemo::STATE_OPEN);
                $message = __('Transaction id %1 updated with status %2.', $transactionId, $status);
            }

            $this->creditmemoComment->setComment($message);
            $this->creditmemoComment->setIsCustomerNotified(1);
            $this->creditmemoComment->setIsVisibleOnFront(1);
            $this->creditmemoComment->setParentId($creditMemo->getEntityId());
            $creditMemo->setComments([$this->creditmemoComment]);
            $this->creditmemoRepository->save($creditMemo);

        } catch (\Exception $e) {

            return $this->refundConfirmResponse->setError('error_request', $e->getMessage());
        }

        return $this->refundConfirmResponse->setMessage("refund confirm successfully");
    }
}
