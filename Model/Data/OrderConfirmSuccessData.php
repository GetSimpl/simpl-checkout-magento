<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface;

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
