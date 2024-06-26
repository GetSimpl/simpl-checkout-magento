<?php
declare(strict_types = 1);
namespace Simpl\Checkout\Plugin\Order;

use Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class SaveSimplFields
{
    /**
     * @var SimplAdditionalFieldsExtensionManagement
     */
    private $extensionManagement;

    public function __construct(SimplAdditionalFieldsExtensionManagement $extensionManagement)
    {
        $this->extensionManagement = $extensionManagement;
    }

    public function beforeSave(OrderRepositoryInterface $subject, Order $order): array
    {
        return [$this->extensionManagement->setDataFromExtension($order)];
    }
}
