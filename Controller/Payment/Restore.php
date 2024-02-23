<?php

namespace Simpl\Checkout\Controller\Payment;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class Restore implements HttpGetActionInterface
{

    protected $checkoutSession;

    protected $resultRedirectFactory;

    protected $orderRepository;

    protected $manager;

    protected $quoteRepository;

    protected $jsonFactory;

    public function __construct(
        CheckoutSession $checkoutSession,
        ManagerInterface $manager,
        OrderRepositoryInterface $orderRepository,
        RedirectFactory $resultRedirectFactory,
        CartRepositoryInterface $quoteRepository,
        JsonFactory $jsonFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->manager = $manager;
        $this->orderRepository = $orderRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->quoteRepository = $quoteRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {

        try {
            // Cancel the last order
            $order = $this->checkoutSession->getLastRealOrder();
            $order->setState(Order::STATE_CANCELED);
            $order->setStatus(Order::STATE_CANCELED);
            $this->orderRepository->save($order);

            // Restore the quote
            $quote = $this->quoteRepository->get($order->getQuoteId());
            $quote->setIsActive(true);
            $this->quoteRepository->save($quote);
            $this->checkoutSession->replaceQuote($quote);

            $data = [
                'status' => 'success',
                'url' => 'checkout/#payment'
            ];
        } catch (\Exception $e) {
            $data = [
                'status' => 'success',
                'url' => 'checkout/cart'
            ];
        }

        $resultJson = $this->jsonFactory->create();
        $resultJson->setData($data);
        return $resultJson;
    }
}
