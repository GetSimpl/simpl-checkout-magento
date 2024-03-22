<?php

namespace Simpl\Checkout\Api;

interface RefundManagementInterface
{

    /**
     * API to confirm the order
     *
     * @param string $creditMemoId
     * @param string $orderId
     * @param string $transactionId
     * @param string $status
     * @return \Simpl\Checkout\Api\Data\ApiDataInterface
     */
    public function confirm($creditMemoId, $orderId, $transactionId, $status);
}
