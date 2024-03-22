<?php

namespace Simpl\Checkout\Api\Data;

interface ApiDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return \Simpl\Checkout\Api\Data\MessageDataInterface|null
     */
    public function getData();

    /**
     * Set Data
     *
     * @param \Simpl\Checkout\Api\Data\MessageDataInterface $data
     * @return $this
     */
    public function setData($data);
}
