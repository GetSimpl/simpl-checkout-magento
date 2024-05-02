<?php
declare(strict_types=1);

namespace Simpl\Checkout\Plugin\Order;

use Simpl\Checkout\Model\Total\SimplAdditionalFields;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Sales\Block\Order\Totals;
use Magento\Sales\Model\Order;

class AddSimplFieldsToTotalsBlock
{
    /**
     * Add Simpl fields to the totals block.
     *
     * @param Totals $subject
     * @param Order  $order
     * @return Order
     */
    public function afterGetOrder(Totals $subject, Order $order): Order
    {
        // Check if the totals are empty or Simpl fields already exist
        if (empty($subject->getTotals()) ||
            $subject->getTotal(SimplAdditionalFields::SIMPL_DISCOUNT) !== false ||
            $subject->getTotal(SimplAdditionalFields::SIMPL_CHARGES) !== false
        ) {
            return $order;
        }

        // Add Simpl discount if available
        if ($simplDiscount = $order->getExtensionAttributes()->getSimplAppliedDiscounts()) {
            $simplDiscountLabel = SimplAdditionalFields::SIMPL_DISCOUNT_LABEL;
            $subject->addTotalBefore(new DataObject([
                'code'  => SimplAdditionalFields::SIMPL_DISCOUNT,
                'value' => $simplDiscount,
                'label' => __($simplDiscountLabel)
            ]), TotalsInterface::KEY_GRAND_TOTAL);
        }

        // Add Simpl charges if available
        if ($simplCharges = $order->getExtensionAttributes()->getSimplAppliedCharges()) {
            $simplChargesLabel = SimplAdditionalFields::SIMPL_CHARGES_LABEL;
            $subject->addTotalBefore(new DataObject([
                'code'  => SimplAdditionalFields::SIMPL_CHARGES,
                'value' => $simplCharges,
                'label' => __($simplChargesLabel)
            ]), TotalsInterface::KEY_GRAND_TOTAL);
        }

        return $order;
    }
}
