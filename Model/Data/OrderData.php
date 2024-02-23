<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\OrderDataInterface;

class OrderData extends ApiResponseData implements OrderDataInterface
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
