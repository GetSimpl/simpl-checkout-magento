<?php

namespace Simpl\Checkout\Model\Data;

class CreditMemoData extends ApiResponseData implements \Simpl\Checkout\Api\Data\CreditMemoDataInterface
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
