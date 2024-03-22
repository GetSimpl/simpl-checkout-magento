<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Model\Simpl\Order;
use Simpl\Checkout\Model\Simpl\User;

class ThankyouPageview extends AbstractEvents
{
    /**
     * @var SimplApi
     */
    protected $simplApi;
    /**
     * @var Order
     */
    protected $order;
    /**
     * @var User
     */
    protected $user;

    /**
     * @param SimplApi $simplApi
     * @param Order $order
     * @param User $user
     * @param Http $request
     * @param Json $json
     */
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

    /**
     * This handle the event by preparing event payload and submit
     *
     * @param array $data
     * @return void
     */
    public function handle($data = [])
    {
        $payload = $this->getFingerprint();
        $payload["order_details"] = $this->order->getOrderDetails();
        try {
            $payload["user_details"] = $this->user->getUserDetails();
        } catch (NoSuchEntityException|LocalizedException $e) {
            $payload["user_details"] = [];
        }
        $this->simplApi->event("thank_you_pageview", $payload, "MerchantPageEvents");
    }
}
