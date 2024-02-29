<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Model\Simpl\Cart;
use Simpl\Checkout\Model\Simpl\User;

class CheckoutPageview extends AbstractEvents {

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
        $this->simplApi->event("checkout_pageview", $payload, "merchant_page_events");
    }
}
