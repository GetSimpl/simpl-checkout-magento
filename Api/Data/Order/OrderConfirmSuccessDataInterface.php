<?php

namespace Simpl\Checkout\Api\Data\Order;

use Simpl\Checkout\Api\Data\ApiResponseDataInterface;

interface OrderConfirmSuccessDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return RedirectionUrlDataInterface|null
     */
    public function getData();

    /**
     * Set Data
     *
     * @param RedirectionUrlDataInterface $data
     * @return $this
     */
    public function setData($data);
}
