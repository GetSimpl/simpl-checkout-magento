<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface;
use Simpl\Checkout\Model\Data\ApiResponseData;

class OrderConfirmSuccessData
    extends ApiResponseData
    implements OrderConfirmSuccessDataInterface
{
    private $data;

    /**
     * @inheritDoc
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData($data) {
        $this->data = $data;
        return $this;
    }
}
