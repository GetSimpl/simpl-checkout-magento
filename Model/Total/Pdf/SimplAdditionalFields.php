<?php

namespace Simpl\Checkout\Model\Total\Pdf;

use Simpl\Checkout\Model\Total\SimplAdditionalFields as SimplFields;

class SimplAdditionalFields extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
{
    /**
     * To show simpl charges and discounts in PDF
     * @return array
     */
    public function getTotalsForDisplay()
    {
        $order = $this->getOrder();
        $fontSize = 10;
        $totals = [];

        if ($amount = $order->getExtensionAttributes()->getSimplAppliedDiscounts()) {
            $amount = $this->getOrder()->formatPriceTxt($amount);
            $totals = [
                [
                    'amount'   => $amount,
                    'label'    => SimplFields::SIMPL_DISCOUNT_LABEL,
                    'font_size'=> $fontSize
                ]
            ];
        }

        if ($amount = $order->getExtensionAttributes()->getSimplAppliedCharges()) {
            $amount = $this->getOrder()->formatPriceTxt($amount);
            $charges = [
                [
                    'amount'   => $amount,
                    'label'    => SimplFields::SIMPL_CHARGES_LABEL,
                    'font_size'=> $fontSize
                ]
            ];
            $totals = array_merge($totals, $charges);
        }

        return $totals;
    }
}
