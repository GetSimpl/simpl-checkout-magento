<?php
declare(strict_types = 1);

namespace Simpl\Checkout\Model\Total\Creditmemo;

use Simpl\Checkout\Model\Total\SimplAdditionalFields as SimplFields;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;

class SimplAdditionalFields extends AbstractTotal
{
    /**
     * @inheritdoc
     */
    public function collect(Creditmemo $creditmemo)
    {
        parent::collect($creditmemo);

        $simplDiscounts = (float)$creditmemo->getOrder()->getExtensionAttributes()->getSimplAppliedDiscounts();
        $simplCharges = (float)$creditmemo->getOrder()->getExtensionAttributes()->getSimplAppliedCharges();
        $creditmemo->setData(SimplFields::SIMPL_DISCOUNT, $simplDiscounts);
        $creditmemo->setData(SimplFields::SIMPL_CHARGES, $simplCharges);

        $total = $simplCharges + $simplDiscounts;
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $total);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $total);

        return $this;
    }
}
