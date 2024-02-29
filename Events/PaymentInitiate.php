<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Model\Simpl\Cart;
use Simpl\Checkout\Model\Simpl\User;

class PaymentInitiate extends AbstractEvents {

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
        $payload["button_text"] = $this->getParam("button_text");
        $this->simplApi->event("payment_initiate", $payload, "merchant_page_events");
    }
}
