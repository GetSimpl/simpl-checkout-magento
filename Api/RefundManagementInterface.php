<?php

namespace Simpl\Checkout\Api;

interface RefundManagementInterface
{

    /**
    "credit_memo_id": "000001",
    "order_id": "00003",
    "transaction_id": "rfnd_askdfhkajshdfkj",
    "status": "REFUND_SUCCESS",
     * @param string $creditMemoId
     * @param string $orderId
     * @param string $transactionId
     * @param string $status
     * @return \Simpl\Checkout\Api\Data\ApiDataInterface
     */
    public function confirm($creditMemoId, $orderId, $transactionId, $status);
}
