<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\ApiDataInterface;

class ApiData extends ApiResponseData implements ApiDataInterface
{
    /**
     * @var mixed
     */
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
