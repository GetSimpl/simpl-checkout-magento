<?php

namespace Simpl\Checkout\Model;

use Simpl\Checkout\Api\Data\ErrorDataInterface;

class RefundManagement implements \Simpl\Checkout\Api\RefundManagementInterface
{

    protected $apiResponseData;

    protected $messageData;

    protected $orderRepository;

    protected $creditmemoRepository;

    protected $creditmemoComment;

    protected $errorData;

    public function __construct(
        \Simpl\Checkout\Api\Data\ApiDataInterface $apiResponseData,
        \Simpl\Checkout\Api\Data\MessageDataInterface $messageData,
        \Simpl\Checkout\Api\Data\ErrorDataInterface $errorData,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Model\Order\CreditmemoRepository $creditmemoRepository,
        \Magento\Sales\Api\Data\CreditmemoCommentInterface $creditmemoComment
    )
    {
        $this->apiResponseData = $apiResponseData;
        $this->messageData = $messageData;
        $this->orderRepository = $orderRepository;
        $this->creditmemoRepository = $creditmemoRepository;
        $this->creditmemoComment = $creditmemoComment;
        $this->errorData = $errorData;
    }

    /**
     * @inheritDoc
     */
    public function confirm($creditMemoId, $orderId, $transactionId, $status)
    {
        $this->apiResponseData->setSuccess(false);

        try {
            $order = $this->orderRepository->get($orderId);
            $creditMemo =  $this->creditmemoRepository->get($creditMemoId);

            if ($creditMemo->getOrderId() != $orderId) {
                throw new \Exception('invalid access');
            }

            if ($status == "REFUND_SUCCESS") {
                $message = __('Refund processed successfully with transaction id %1',$transactionId);
            } else {
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
            $this->errorData->setCode('400');
            $this->errorData->setMessage($e->getMessage());
            $this->apiResponseData->setError($this->errorData);
            return $this->apiResponseData;
        }

        $this->apiResponseData->setData($this->messageData);


        return $this->apiResponseData;
    }
}
