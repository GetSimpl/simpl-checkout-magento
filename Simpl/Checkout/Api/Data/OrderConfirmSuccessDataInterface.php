<?php

namespace Simpl\Checkout\Api\Data;

interface OrderConfirmSuccessDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return \Simpl\Checkout\Api\Data\RedirectionUrlDataInterface|null
     */
    public function getData();

    /**
     * Set Data
     *
     * @param \Simpl\Checkout\Api\Data\RedirectionUrlDataInterface $data
     * @return $this
     */
    public function setData($data);
}
