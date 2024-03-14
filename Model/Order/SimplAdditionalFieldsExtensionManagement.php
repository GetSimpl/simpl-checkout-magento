<?php
declare(strict_types = 1);
namespace Simpl\Checkout\Model\Order;

use Simpl\Checkout\Model\Total\SimplAdditionalFields;
use Magento\Quote\Model\Quote\Address as QuoteAddress;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Model\Order;

class SimplAdditionalFieldsExtensionManagement
{
    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    public function __construct(OrderExtensionFactory $orderExtensionFactory)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    public function setExtensionFromData(Order $order): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);
        $orderExtension->setSimplAppliedDiscounts($order->getData(SimplAdditionalFields::SIMPL_DISCOUNT));
        $orderExtension->setSimplAppliedCharges($order->getData(SimplAdditionalFields::SIMPL_CHARGES));
        return $order;
    }

    public function setExtensionFromAddressData(Order $order, QuoteAddress $address): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);
        $orderExtension->setSimplAppliedDiscounts($address->getData(SimplAdditionalFields::SIMPL_DISCOUNT));
        $orderExtension->setSimplAppliedCharges($address->getData(SimplAdditionalFields::SIMPL_CHARGES));
        return $order;
    }

    public function setDataFromExtension(Order $order): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);
        $order->setData(SimplAdditionalFields::SIMPL_DISCOUNT, $orderExtension->getSimplAppliedDiscounts());
        $order->setData(SimplAdditionalFields::SIMPL_CHARGES, $orderExtension->getSimplAppliedCharges());
        return $order;
    }

    private function getOrInitOrderExtension(Order $order): OrderExtensionInterface
    {
        $orderExtension = $order->getExtensionAttributes();
        if ($orderExtension === null) {
            $orderExtension = $this->orderExtensionFactory->create();
            $order->setExtensionAttributes($orderExtension);

            return $orderExtension;
        }

        return $orderExtension;
    }
}
