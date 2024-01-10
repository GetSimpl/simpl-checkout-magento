<?php

namespace Simpl\Checkout\Model\Data;

class OrderConfirmSuccessData
    extends ApiResponseData
    implements \Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface
{
    private $data;

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
