<?php
declare(strict_types=1);

namespace Simpl\Checkout\Plugin\Order;

use Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement;
use Magento\Sales\Model\Order;

class LoadSimplFields
{
    /**
     * @var SimplAdditionalFieldsExtensionManagement
     */
    private $extensionManagement;

    /**
     * @param SimplAdditionalFieldsExtensionManagement $extensionManagement
     */
    public function __construct(SimplAdditionalFieldsExtensionManagement $extensionManagement)
    {
        $this->extensionManagement = $extensionManagement;
    }

    /**
     * After loading an order, set Simpl additional fields data.
     *
     * @param Order $subject
     * @param Order $returnedOrder
     * @return Order
     */
    public function afterLoad(Order $subject, Order $returnedOrder): Order
    {
        return $this->extensionManagement->setExtensionFromData($returnedOrder);
    }
}
