<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Model\Simpl\Cart;
use Simpl\Checkout\Model\Simpl\User;

class PaymentInfoSubmitted extends AbstractEvents {

    protected $simplApi;

    protected $cart;

    protected $user;

    public function __construct(
        SimplApi $simplApi,
        Cart $cart,
        User $user,
        Http $request,
        Json $json
    ) {
        $this->simplApi = $simplApi;
        $this->cart = $cart;
        $this->user = $user;
        parent::__construct($request, $json);
    }

    public function handle($data = [])
    {
        $payload = $this->getFingerprint();
        $payload["cart_details"] = $this->cart->getCartDetails();
        $payload["user_details"] = $this->user->getUserDetails();
        $payload["payment_mode_details"] = $this->cart->getPaymentModeDetails();
        $this->simplApi->event("payment_info_submitted", $payload, "merchant_page_events");
    }
}
