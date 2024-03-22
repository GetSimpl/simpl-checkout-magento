<?php

namespace Simpl\Checkout\Api;

use Simpl\Checkout\Api\Data\OrderDataInterface;

interface OrderDetailsInterface
{

    /**
     * API to get the order details by order id
     *
     * @param string $orderId
     * @return OrderDataInterface
     */
    public function get(string $orderId);
}
