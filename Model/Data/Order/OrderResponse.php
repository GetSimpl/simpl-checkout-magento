<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\OrderDataInterface;

class OrderResponse
{
    /**
     * @var ErrorDataInterface
     */
    protected $errorData;

    /**
     * @var OrderDataInterface
     */
    protected $orderData;

    /**
     * @param ErrorDataInterface $errorData
     * @param OrderDataInterface $orderData
     */
    public function __construct(
        ErrorDataInterface $errorData,
        OrderDataInterface $orderData
    ) {
        $this->errorData = $errorData;
        $this->orderData = $orderData;
    }

    /**
     * @param $order
     * @return OrderDataInterface
     */
    public function setOrder($order) {

        $this->orderData->setData($order);
        $this->orderData->setSuccess(true);
        return $this->orderData;
    }

    /**
     * @return OrderDataInterface
     */
    public function orderNotFoundError() {

        $this->orderData->setSuccess(false);
        $this->errorData->setCode("order_not_found");
        $this->errorData->setMessage("Order not found");
        $this->orderData->setError($this->errorData);
        return $this->orderData;
    }
}
