<?php
declare(strict_types = 1);

namespace Simpl\Checkout\Model\Total\Invoice;

use Simpl\Checkout\Model\Total\SimplAdditionalFields as SimplFields;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class SimplAdditionalFields extends AbstractTotal
{
    /**
     * @inheritdoc
     */
    public function collect(Invoice $invoice)
    {
        parent::collect($invoice);

        if ($invoice->getOrder()->getExtensionAttributes()) {
            $simplDiscounts = (float)$invoice->getOrder()->getExtensionAttributes()->getSimplAppliedDiscounts();
            $invoice->setData(SimplFields::SIMPL_DISCOUNT, $simplDiscounts);

            $simplCharges = (float)$invoice->getOrder()->getExtensionAttributes()->getSimplAppliedCharges();
            $invoice->setData(SimplFields::SIMPL_CHARGES, $simplCharges);
        }

        return $this;
    }
}
