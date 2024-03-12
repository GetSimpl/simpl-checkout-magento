<?php

namespace Simpl\Checkout\Model\Simpl;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;

class User {

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
     * Function to get user details from session
     * @return array
     */
    public function getUserDetails($isOrder = false)
    {

        $userDetails["is_logged_in"] = false;
        if ($this->customerSession->isLoggedIn()) {
            $userDetails = $this->customerSession->getCustomer()->getData();
            $userDetails["password_hash"] = null;
            $userDetails["rp_token"] = null;
            $userDetails["rp_token_created_at"] = null;
            $userDetails["is_logged_in"] = true;
        }

        if ($isOrder) {
            $order = $this->checkoutSession->getLastRealOrder();
            if ($order->getIsNotVirtual()) {
                $userDetails["shipping_address"] = $order->getShippingAddress()->getData();
            }
            $userDetails["billing_address"] = $order->getBillingAddress()->getData();
        } else {
            $quote = $this->checkoutSession->getQuote();
            if (!$quote->getIsVirtual()) {
                $userDetails["shipping_address"] = $quote->getShippingAddress()->getData();
            }
            $userDetails["billing_address"] = $quote->getBillingAddress()->getData();
        }

        return $userDetails;
    }
}
