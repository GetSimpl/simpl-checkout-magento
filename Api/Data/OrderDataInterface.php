<?php

namespace Simpl\Checkout\Api\Data;

interface OrderDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getData();

    /**
     * Set Data
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $data
     * @return $this
     */
    public function setData($data);
}
