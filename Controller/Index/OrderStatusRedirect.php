<?php

declare(strict_types=1);

namespace Simpl\Checkout\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class OrderStatusRedirect implements CsrfAwareActionInterface, HttpPostActionInterface, HttpGetActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        RequestInterface $request,
        RedirectFactory $redirectFactory
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * Redirect to another controller action with parameters.
     *
     * @return ResponseInterface|Redirect|ResultInterface
     */
    public function execute()
    {
        $params = $this->request->getParams();
        $resultRedirect = $this->redirectFactory->create();
        $resultRedirect->setPath('simpl/index/orderstatusupdateonredirect', $params);
        return $resultRedirect;
    }

    /**
     * Create exception in case CSRF validation failed. Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * Perform custom request validation. Return null if default validation is needed.
     *
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
