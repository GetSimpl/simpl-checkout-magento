<?php

namespace Simpl\Checkout\Api\Data\Order;

use Simpl\Checkout\Api\Data\ApiResponseDataInterface;

interface OrderConfirmSuccessDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return \Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface|null
     */
    public function getData();

    /**
     * Set Data
     *
     * @param \Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface $data
     * @return $this
     */
    public function setData($data);
}
