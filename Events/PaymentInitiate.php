<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Model\Simpl\Cart;
use Simpl\Checkout\Model\Simpl\User;

class PaymentInitiate extends AbstractEvents
{
    /**
     * @var SimplApi
     */
    protected $simplApi;
    /**
     * @var Cart
     */
    protected $cart;
    /**
     * @var User
     */
    protected $user;

    /**
     * @param SimplApi $simplApi
     * @param Cart $cart
     * @param User $user
     * @param Http $request
     * @param Json $json
     */
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

    /**
     * This handle the event by preparing event payload and submit
     *
     * @param array $data
     * @return void
     */
    public function handle($data = [])
    {
        $payload = $this->getFingerprint();
        $payload["cart_details"] = $this->cart->getCartDetails();
        try {
            $payload["user_details"] = $this->user->getUserDetails();
        } catch (NoSuchEntityException|LocalizedException $e) {
            $payload["user_details"] = [];
        }
        try {
            $payload["payment_mode_details"] = $this->cart->getPaymentModeDetails();
        } catch (NoSuchEntityException|LocalizedException $e) {
            $payload["payment_mode_details"] = [];
        }
        $payload["button_text"] = $this->getParam("button_text");
        $this->simplApi->event("payment_initiate", $payload, "MerchantPageEvents");
    }
}
