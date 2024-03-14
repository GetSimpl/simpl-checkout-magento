<?php
declare(strict_types = 1);

namespace Simpl\Checkout\Model\Total\Invoice;

class SimplAdditionalFields extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{
    /**
     * @inheritdoc
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        parent::collect($invoice);

        if ($invoice->getOrder()->getExtensionAttributes()) {
            $simplDiscounts = (float)$invoice->getOrder()->getExtensionAttributes()->getSimplAppliedDiscounts();
            $invoice->setData(\Simpl\Checkout\Model\Total\SimplAdditionalFields::SIMPL_DISCOUNT, $simplDiscounts);

            $simplCharges = (float)$invoice->getOrder()->getExtensionAttributes()->getSimplAppliedCharges();
            $invoice->setData(\Simpl\Checkout\Model\Total\SimplAdditionalFields::SIMPL_CHARGES, $simplCharges);
        }

        return $this;
    }
}
