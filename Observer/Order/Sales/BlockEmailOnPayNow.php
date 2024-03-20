<?php

namespace Simpl\Checkout\Observer\Order\Sales;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

class BlockEmailOnPayNow implements ObserverInterface
{

    /**
     * Sales order place after observer to stop sending mails before payment
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /** @var Order $order */
        $order = $observer->getEvent()->getData('order');
        if ($order->getPayment()->getMethod() == 'simplcheckout') {
            $order->setCanSendNewEmailFlag(false);
            $order->addCommentToStatusHistory("Customer redirected to simpl for payment");
        }
    }
}
