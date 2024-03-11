<?php
declare(strict_types = 1);
namespace Simpl\Checkout\Plugin\Order;

use Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;

class LoadSimplFieldsOnCollection
{
    /**
     * @var SimplAdditionalFieldsExtensionManagement
     */
    private $extensionManagement;

    public function __construct(SimplAdditionalFieldsExtensionManagement $extensionManagement)
    {
        $this->extensionManagement = $extensionManagement;
    }

    public function afterGetItems(OrderCollection $subject, array $orders): array
    {
        return array_map(function (Order $order) {
            return $this->extensionManagement->setExtensionFromData($order);
        }, $orders);
    }
}
