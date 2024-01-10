<?php

namespace Simpl\Checkout\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Sales\Api\Data\TransactionInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Payment\Transaction;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\RedirectFactory;
use Psr\Log\LoggerInterface;

class Update  implements CsrfAwareActionInterface, HttpPostActionInterface, HttpGetActionInterface
{

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var Transaction\Builder
     */
    private $transactionBuilder;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var RedirectFactory
     */
    private $resultRedirectFactory;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        RequestInterface $request,
        Transaction\Builder $transactionBuilder,
        Session $session,
        RedirectFactory $resultRedirectFactory,
        ManagerInterface $messageManager,
        LoggerInterface $logger
    )
    {
        $this->orderRepository = $orderRepository;
        $this->request = $request;
        $this->transactionBuilder = $transactionBuilder;
        $this->session = $session;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->logger = $logger;
        $this->messageManager = $messageManager;
    }

    /**
     * Execute action based on request and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $orderId = $this->request->getParam('order_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('checkout/index/index');

        // Validate order id
        if (!$orderId) {
            $this->messageManager->addErrorMessage(__('Invalid Access'));
            return $resultRedirect;
        }

        //TODO LOGIC TO VALIDATE PAYMENT
        try {
            // Process params
            $comment = 'Response from payment: ';
            $AMOUNT = '0'; //TODO GET PAID AMOUNT FROM SIMPL
            $this->logger->info('Back from payment gateway');
            $order = $this->orderRepository->get($orderId);

            // Validate payment amount
            if($AMOUNT != $order->getBaseGrandTotal() AND
                $AMOUNT != $order->getSubtotal()) {
                $this->logger->info('Paid amount different from order amount for ' . $orderId);
                $this->messageManager->addErrorMessage(__('Paid amount different from order amount'));
                $this->messageManager->addErrorMessage(__('Invalid Access'));
                return $resultRedirect;
            }

            // Save transaction details to order
            $order->addCommentToStatusHistory($comment);
            $order->setState(Order::STATE_PROCESSING);
            $order->setStatus(Order::STATE_PROCESSING);
            $order->setCustomerNoteNotify(true);
            $this->orderRepository->save($order);
        } catch (\Exception $e) {
            $this->logger->info($e->getMessage());
            $this->messageManager->addErrorMessage(__('Error processing payment'));
            return $resultRedirect;
        }

        // Redirect to success page
        $this->logger->info('Payment Success, Order created successfully');
        $this->messageManager->addSuccessMessage(__('Payment Success, Order created successfully'));
        $resultRedirect->setPath('checkout/onepage/success');
        return $resultRedirect;
    }

    /**
     * Create exception in case CSRF validation failed.
     * Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * Perform custom request validation.
     * Return null if default validation is needed.
     *
     * @param RequestInterface $request
     *
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
