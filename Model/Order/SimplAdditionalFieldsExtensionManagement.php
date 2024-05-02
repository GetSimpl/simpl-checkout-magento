<?php
declare(strict_types=1);

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

    /**
     * SimplAdditionalFieldsExtensionManagement constructor.
     *
     * @param OrderExtensionFactory $orderExtensionFactory
     */
    public function __construct(OrderExtensionFactory $orderExtensionFactory)
    {
        $this->orderExtensionFactory = $orderExtensionFactory;
    }

    /**
     * Set extension data from order.
     *
     * @param Order $order
     * @return Order
     */
    public function setExtensionFromData(Order $order): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);
        $orderExtension->setSimplAppliedDiscounts($order->getData(SimplAdditionalFields::SIMPL_DISCOUNT));
        $orderExtension->setSimplAppliedCharges($order->getData(SimplAdditionalFields::SIMPL_CHARGES));
        return $order;
    }

    /**
     * Set extension data from address.
     *
     * @param Order         $order
     * @param QuoteAddress  $address
     * @return Order
     */
    public function setExtensionFromAddressData(Order $order, QuoteAddress $address): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);
        $orderExtension->setSimplAppliedDiscounts($address->getData(SimplAdditionalFields::SIMPL_DISCOUNT));
        $orderExtension->setSimplAppliedCharges($address->getData(SimplAdditionalFields::SIMPL_CHARGES));
        return $order;
    }

    /**
     * Set data from extension.
     *
     * @param Order $order
     * @return Order
     */
    public function setDataFromExtension(Order $order): Order
    {
        $orderExtension = $this->getOrInitOrderExtension($order);
        $order->setData(SimplAdditionalFields::SIMPL_DISCOUNT, $orderExtension->getSimplAppliedDiscounts());
        $order->setData(SimplAdditionalFields::SIMPL_CHARGES, $orderExtension->getSimplAppliedCharges());
        return $order;
    }

    /**
     * Get or initialize order extension.
     *
     * @param Order $order
     * @return OrderExtensionInterface
     */
    private function getOrInitOrderExtension(Order $order): OrderExtensionInterface
    {
        $orderExtension = $order->getExtensionAttributes();
        if ($orderExtension === null) {
            $orderExtension = $this->orderExtensionFactory->create();
            $order->setExtensionAttributes($orderExtension);
        }
        return $orderExtension;
    }
}
