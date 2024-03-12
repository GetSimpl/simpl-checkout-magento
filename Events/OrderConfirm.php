<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Model\Simpl\Order;
use Simpl\Checkout\Model\Simpl\User;

class OrderConfirm extends AbstractEvents {

    protected $simplApi;

    protected $order;

    protected $user;

    public function __construct(
        SimplApi $simplApi,
        Order $order,
        User $user,
        Http $request,
        Json $json
    ) {
        $this->simplApi = $simplApi;
        $this->order = $order;
        $this->user = $user;
        parent::__construct($request, $json);
    }

    public function handle($data = [])
    {
        $payload = $this->getFingerprint();
        $payload["order_details"] = $this->order->getOrderDetails();
        $payload["user_details"] = $this->user->getUserDetails();
        $this->simplApi->event("order_confirm", $payload, "MerchantPageEvents");
    }
}
