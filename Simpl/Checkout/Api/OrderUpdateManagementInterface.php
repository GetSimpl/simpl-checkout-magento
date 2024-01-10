<?php

namespace Simpl\Checkout\Api;

use Simpl\Checkout\Api\Data\AppliedChargesDataInterface;
use Simpl\Checkout\Api\Data\AppliedDiscountsDataInterface;
use Simpl\Checkout\Api\Data\PaymentDataInterface;
use Simpl\Checkout\Api\Data\TransactionDataInterface;
use Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface;

interface OrderUpdateManagementInterface
{
    /**
     * Confirm Simpl Checkout Order by ID
     * @param string $orderId
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     * @param AppliedChargesDataInterface[] $appliedCharges
     * @param AppliedDiscountsDataInterface[] $appliedDiscounts
     * @return \Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function confirm($orderId, $payment, $transaction, $appliedCharges, $appliedDiscounts);

    /**
     * Confirm Simpl Checkout Order by ID
     * @param string $orderId
     * @param PaymentDataInterface $payment
     * @param TransactionDataInterface $transaction
     * @return \Simpl\Checkout\Api\Data\OrderConfirmSuccessDataInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function update($orderId, $payment, $transaction);
}
