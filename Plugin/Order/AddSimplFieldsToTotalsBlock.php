<?php
declare(strict_types = 1);
namespace Simpl\Checkout\Plugin\Order;

use Simpl\Checkout\Model\Total\SimplAdditionalFields;
use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\TotalsInterface;
use Magento\Sales\Block\Order\Totals;
use Magento\Sales\Model\Order;

class AddSimplFieldsToTotalsBlock
{
    public function afterGetOrder(Totals $subject, Order $order): Order
    {
        if (empty($subject->getTotals())) {
            return $order;
        }

        if ($subject->getTotal(SimplAdditionalFields::SIMPL_DISCOUNT) !== false and
            $subject->getTotal(SimplAdditionalFields::SIMPL_CHARGES) !== false
        ) {
            return $order;
        }

        if ($simplDiscount = $order->getExtensionAttributes()->getSimplAppliedDiscounts()) {
            $subject->addTotalBefore(new DataObject([
                'code' => SimplAdditionalFields::SIMPL_DISCOUNT,
                'value' => $simplDiscount,
                'label' => __(SimplAdditionalFields::SIMPL_DISCOUNT_LABEL)
            ]), TotalsInterface::KEY_GRAND_TOTAL);
        }

        if ($simplCharges = $order->getExtensionAttributes()->getSimplAppliedCharges()) {
            $subject->addTotalBefore(new DataObject([
                'code' => SimplAdditionalFields::SIMPL_CHARGES,
                'value' => $simplCharges,
                'label' => __(SimplAdditionalFields::SIMPL_CHARGES_LABEL)
            ]), TotalsInterface::KEY_GRAND_TOTAL);
        }

        return $order;
    }
}
