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
use Magento\Framework\App\Request\Http;
use Simpl\Checkout\Logger\Logger;
use Magento\Sales\Model\OrderFactory;

class Restore implements HttpGetActionInterface
{
    protected $checkoutSession;

    protected $resultRedirectFactory;

    protected $orderRepository;

    protected $manager;

    protected $quoteRepository;

    protected $httpRequest;

    protected $logger;

    protected $orderFactory;

    /**
     * @param CheckoutSession $checkoutSession
     * @param ManagerInterface $manager
     * @param OrderRepositoryInterface $orderRepository
     * @param RedirectFactory $resultRedirectFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param Http $httpRequest
     * @param Logger $logger
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        ManagerInterface $manager,
        OrderRepositoryInterface $orderRepository,
        RedirectFactory $resultRedirectFactory,
        CartRepositoryInterface $quoteRepository,
        Http $httpRequest,
        Logger $logger,
        OrderFactory $orderFactory
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->manager = $manager;
        $this->orderRepository = $orderRepository;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->quoteRepository = $quoteRepository;
        $this->httpRequest = $httpRequest;
        $this->logger = $logger;
        $this->orderFactory = $orderFactory;
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
            $id = $this->httpRequest->getParam('id');
            $quote = $this->quoteRepository->get($id);
            echo $quote->getId();
            $incrementId = $quote->getReservedOrderId();

            $orderModel = $this->orderFactory->create();
            $order = $orderModel->loadByIncrementId($incrementId);
            if ($order && $order->getId()) {
                $order->setState(Order::STATE_CANCELED);
                $order->setStatus(Order::STATE_CANCELED);
                $this->orderRepository->save($order);
            }

            // Restore the quote
            $quote->setIsActive(true);
            $this->quoteRepository->save($quote);
            $this->checkoutSession->replaceQuote($quote);
            $this->manager->addWarningMessage(__("We saw you were about to order. Let's give it another go."));
        } catch (\Exception $e) {
            $this->manager->addErrorMessage("Unauthorized action");
            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
        }
        $redirect = $this->resultRedirectFactory->create();
        $redirect->setPath('checkout/cart');
        return $redirect;
    }
}
