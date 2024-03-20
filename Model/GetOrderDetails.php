<?php

namespace Simpl\Checkout\Model;

use Simpl\Checkout\Api\OrderDetailsInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Simpl\Checkout\Model\Data\Order\GetOrderResponse;
use Simpl\Checkout\Logger\Logger;

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
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param GetOrderResponse $getOrderResponse
     * @param Logger $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        GetOrderResponse $getOrderResponse,
        Logger $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->getOrderResponse = $getOrderResponse;
        $this->logger = $logger;
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
            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            return $this->getOrderResponse->orderNotFoundError();
        }
    }
}
