<?php

namespace Simpl\Checkout\Model;

use Simpl\Checkout\Api\OrderDetailsInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Simpl\Checkout\Model\Data\Order\GetOrderResponse;
use Simpl\Checkout\Helper\Alert;

class GetOrderDetails implements OrderDetailsInterface
{

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var GetOrderResponse
     */
    protected $getOrderResponse;

    protected $alert;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param GetOrderResponse $getOrderResponse
     * @param Alert $alert
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        GetOrderResponse $getOrderResponse,
        Alert $alert
    ) {
        $this->orderRepository = $orderRepository;
        $this->getOrderResponse = $getOrderResponse;
        $this->alert = $alert;
    }

    /**
     * @inheritDoc
     */
    public function get(string $orderId)
    {
        try {
            $order = $this->orderRepository->get($orderId);
            return $this->getOrderResponse->setOrder($order);
        } catch (\Exception $e) {
            $this->alert->alert($e->getMessage(), 'ERROR', $e->getTraceAsString());
            return $this->getOrderResponse->orderNotFoundError();
        }
    }
}
