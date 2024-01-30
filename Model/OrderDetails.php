<?php

namespace Simpl\Checkout\Model;

use Simpl\Checkout\Api\OrderDetailsInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Simpl\Checkout\Model\Data\Order\Response;

class OrderDetails implements OrderDetailsInterface {

    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param Response $response
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Response $response
    ) {
        $this->orderRepository = $orderRepository;
        $this->response = $response;
    }

    /**
     * @inheritDoc
     */
    public function get(string $orderId) {

        try {

            $order = $this->orderRepository->get($orderId);
            return $this->response->setOrder($order);
        } catch (\Exception $e) {

            return $this->response->orderNotFoundError();
        }
    }
}
