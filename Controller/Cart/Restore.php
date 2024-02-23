<?php

namespace Simpl\Checkout\Controller\Cart;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class Restore implements HttpGetActionInterface
{
    protected $checkoutSession;

    protected $resultRedirectFactory;

    protected $orderRepository;

    protected $manager;

    protected $quoteRepository;

    /**
     * @param CheckoutSession $checkoutSession
     * @param ManagerInterface $manager
     * @param OrderRepositoryInterface $orderRepository
     * @param RedirectFactory $resultRedirectFactory
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ManagerInterface $manager,
        OrderRepositoryInterface $orderRepository,
        RedirectFactory $resultRedirectFactory,
        CartRepositoryInterface $quoteRepository
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->manager = $manager;
        $this->orderRepository = $orderRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
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
        } catch (\Exception $e) {
        }
        $this->manager->addWarningMessage(__("We saw you were about to order. Let's give it another go."));
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('checkout/cart');
        return $redirect;
    }
}