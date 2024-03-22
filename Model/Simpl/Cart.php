<?php

namespace Simpl\Checkout\Model\Simpl;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;

class Cart {

    protected $customerSession;
    protected $checkoutSession;

    public function __construct(
        CustomerSession $customerSession,
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
    }


    /**
     * To get cart details from session
     * @return array
     */
    public function getCartDetails()
    {

        $cartDetails = [];
        $cartDetails = $this->checkoutSession->getQuote()->getData();
        $itemsData = [];
        $items = $this->checkoutSession->getQuote()->getAllItems();
        foreach ($items as $item) {
            $itemsData[] = $item->getData();
        }
        $cartDetails["items"] = $itemsData;

        return $cartDetails;
    }

    public function getPaymentModeDetails()
    {
        return $this->checkoutSession->getQuote()->getPayment()->getData();
    }
}
