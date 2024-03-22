<?php

namespace Simpl\Checkout\Api;

use Simpl\Checkout\Api\Data\OrderDataInterface;

interface OrderDetailsInterface
{

    /**
     * @param string $orderId
     * @return OrderDataInterface
     */
    public function get(string $orderId);
}
