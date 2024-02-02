<?php

namespace Simpl\Checkout\Api\Data;

interface ApiDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return MessageDataInterface|null
     */
    public function getData();

    /**
     * Set Data
     *
     * @param MessageDataInterface $data
     * @return $this
     */
    public function setData($data);
}
