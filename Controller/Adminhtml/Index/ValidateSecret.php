<?php

namespace Simpl\Checkout\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Simpl\Checkout\Helper\SimplApi;

class ValidateSecret implements HttpPostActionInterface {

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var Json
     */
    protected $serializer;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Http
     */
    protected $http;

    /**
     * @var RequestInterface
     */
    protected $request;

    protected $simplApi;

    /**
     * @param PageFactory $resultPageFactory
     * @param Json $json
     * @param LoggerInterface $logger
     * @param Http $http
     * @param RequestInterface $request
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Json $json,
        LoggerInterface $logger,
        Http $http,
        RequestInterface $request,
        SimplApi $simplApi
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->serializer = $json;
        $this->logger = $logger;
        $this->http = $http;
        $this->request = $request;
        $this->simplApi = $simplApi;
    }

    /**
     * Validate secret
     *
     */
    public function execute() {
        try {
            $secret = $this->request->getParam('client_secret');

            if ($this->simplApi->install($secret)) {
                $response = [
                    'status'=> true,
                    'message'=>'Congratulations! Valid Credentials'
                ];
            } else {
                $response = [
                    'status'=> false,
                    'message'=>'Invalid Credentials!'
                ];
            }

        } catch (LocalizedException $e) {
            $response = ['status'=> false, 'message'=>$e->getMessage()];
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $response = ['status'=> false, 'message'=>$e->getMessage()];
        }
        return $this->jsonResponse($response);
    }

    /**
     * Create json response
     */
    private function jsonResponse($response = '') {
        $this->http->getHeaders()->clearHeaders();
        $this->http->setHeader('Content-Type', 'application/json');
        return $this->http->setBody(
            $this->serializer->serialize($response)
        );
    }
}
