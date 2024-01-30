<?php

namespace Simpl\Checkout\Model;

use Magento\Sales\Api\Data\CreditmemoCommentInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\CreditmemoRepository;
use Simpl\Checkout\Api\Data\ApiDataInterface;
use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\MessageDataInterface;
use Simpl\Checkout\Api\RefundManagementInterface;
use Simpl\Checkout\Helper\SimplApi;

class RefundManagement implements RefundManagementInterface {

    protected $apiResponseData;

    protected $messageData;

    protected $orderRepository;

    protected $creditmemoRepository;

    protected $creditmemoComment;

    protected $errorData;

    protected $simplApi;

    /**
     * @param ApiDataInterface $apiResponseData
     * @param MessageDataInterface $messageData
     * @param ErrorDataInterface $errorData
     * @param OrderRepositoryInterface $orderRepository
     * @param CreditmemoRepository $creditmemoRepository
     * @param CreditmemoCommentInterface $creditmemoComment
     * @param SimplApi $simplApi
     */
    public function __construct(
        ApiDataInterface    $apiResponseData,
        MessageDataInterface    $messageData,
        ErrorDataInterface  $errorData,
        OrderRepositoryInterface    $orderRepository,
        CreditmemoRepository    $creditmemoRepository,
        CreditmemoCommentInterface  $creditmemoComment,
        SimplApi    $simplApi
    ) {
        $this->apiResponseData = $apiResponseData;
        $this->messageData = $messageData;
        $this->orderRepository = $orderRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->creditmemoComment = $creditmemoComment;
        $this->errorData = $errorData;
        $this->simplApi = $simplApi;
    }

    /**
     * @inheritDoc
     */
    public function confirm($creditMemoId, $orderId, $transactionId, $status) {

        $this->apiResponseData->setSuccess(false);

        try {
            $creditMemo =  $this->creditmemoRepository->get($creditMemoId);

            if ($creditMemo->getOrderId() != $orderId) {
                throw new \Exception('invalid access');
            }

            if (!$this->simplApi->validateRefund($creditMemoId, $orderId, $transactionId, $status)) {
                $this->errorData->setCode('validation_failed');
                $this->errorData->setMessage('Validation failed');
                $this->apiResponseData->setError($this->errorData);
                return $this->apiResponseData;
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

            $this->apiResponseData->setSuccess(true);
            $this->messageData->setMessage("refund confirm successfully");

        } catch (\Exception $e) {
            $this->errorData->setCode('error_request');
            $this->errorData->setMessage($e->getMessage());
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        $this->apiResponseData->setData($this->messageData);

        return $this->apiResponseData;
    }
}
