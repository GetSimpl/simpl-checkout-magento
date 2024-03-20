<?php

namespace Simpl\Checkout\Plugin;

use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Service\OrderService;
use Simpl\Checkout\Helper\Config;
use Simpl\Checkout\Helper\SimplApi;
use Magento\Framework\Exception\LocalizedException;
use Simpl\Checkout\Logger\Logger;

class OrderCancel
{
    /**
     * @var SimplApi
     */
    protected $simplApi;
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * OrderCancel constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param SimplApi $simplApi
     * @param Logger $logger
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SimplApi $simplApi,
        Logger $logger
    ) {
        $this->orderRepository = $orderRepository;
        $this->simplApi = $simplApi;
        $this->logger = $logger;
    }

    /**
     * Before cancelling an order, trigger cancel action in Simpl if payment method matches.
     *
     * @param OrderService $subject
     * @param int $id
     * @return array
     * @throws LocalizedException
     */
    public function beforeCancel(OrderService $subject, $id): array
    {
        try {
            $order = $this->orderRepository->get($id);
            if ($order->getPayment()->getMethod() == Config::KEY_PAYMENT_CODE) {
                $orderId = $order->getId();
                $data["order_id"] = $orderId;
                $data["currency"] = $order->getBaseCurrencyCode();
                $data["reason"] = "admin triggered cancel";
                if (!$this->simplApi->cancel($orderId, $data)) {
                    throw new LocalizedException(__('Error in Simpl API call'));
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            throw new LocalizedException(
                __('Order cancellation unsuccessful. Please contact Simpl at merchantsupport@getsimpl.com')
            );
        }
        return [$id];
    }
}
