<?php

namespace Simpl\Checkout\Model;

use Simpl\Checkout\Api\OrderDetailsInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Simpl\Checkout\Model\Data\Order\GetOrderResponse;
use Simpl\Checkout\Helper\SimplApi;

class GetOrderDetails implements OrderDetailsInterface {

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var GetOrderResponse
     */
    protected $getOrderResponse;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param GetOrderResponse $response
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        GetOrderResponse $getOrderResponse,
        SimplApi $simplApi
    ) {
        $this->orderRepository = $orderRepository;
        $this->getOrderResponse = $getOrderResponse;
        $this->simplApi = $simplApi;
    }

    /**
     * @inheritDoc
     */
    public function get(string $orderId) {

        try {

            $order = $this->orderRepository->get($orderId);
            return $this->getOrderResponse->setOrder($order);
        } catch (\Exception $e) {
            $this->simplApi->alert($e->getMessage(), 'INFO', $e->getTraceAsString());
            return $this->getOrderResponse->orderNotFoundError();
        }
    }
}
