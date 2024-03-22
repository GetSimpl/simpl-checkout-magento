<?php

namespace Simpl\Checkout\Api\Data;

interface CreditMemoDataInterface extends ApiResponseDataInterface
{
    /**
     * Get Data
     *
     * @return \Magento\Sales\Api\Data\CreditmemoInterface
     */
    public function getData();

    /**
     * Set Data
     *
     * @param \Magento\Sales\Api\Data\CreditmemoInterface $data
     * @return $this
     */
    public function setData($data);
}
