<?php

namespace Simpl\Checkout\Api;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Simpl\Checkout\Api\Data\Order\AppliedChargesDataInterface;
use Simpl\Checkout\Api\Data\Order\AppliedDiscountsDataInterface;
use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;
use Simpl\Checkout\Api\Data\Order\TransactionDataInterface;
use Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface;

interface OrderConfirmManagementInterface
{
    /**
     * Confirm Simpl Checkout Order by ID
     *
     * @param string $orderId
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     * @param AppliedChargesDataInterface[] $appliedCharges
     * @param AppliedDiscountsDataInterface[] $appliedDiscounts
     * @return OrderConfirmSuccessDataInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function confirm($orderId, $payment, $transaction, $appliedCharges, $appliedDiscounts);
}
