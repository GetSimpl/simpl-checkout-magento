<?php

namespace Simpl\Checkout\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;
use Simpl\Checkout\Api\Data\Order\TransactionDataInterface;
use Simpl\Checkout\Api\Data\ApiDataInterface;

interface OrderUpdateManagementInterface
{
    /**
     * Confirm Simpl Checkout Order by ID
     *
     * @param string $orderId
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     * @return ApiDataInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function update($orderId, $payment, $transaction);
}
