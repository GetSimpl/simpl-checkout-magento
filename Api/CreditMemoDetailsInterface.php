<?php

namespace Simpl\Checkout\Api;

use Simpl\Checkout\Api\Data\CreditMemoDataInterface;

interface CreditMemoDetailsInterface
{
    /**
     * @param string $orderId
     * @param string $creditMemoId
     * @return CreditMemoDataInterface
     */
    public function getCreditMemo($orderId, $creditMemoId);
}
