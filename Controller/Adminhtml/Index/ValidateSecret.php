<?php

namespace Simpl\Checkout\Controller\Adminhtml\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Simpl\Checkout\Helper\SimplApi;
use Simpl\Checkout\Logger\Logger;

class ValidateSecret implements HttpPostActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Json
     */
    protected $serializer;
    /**
     * @var Logger
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
    /**
     * @var SimplApi
     */
    protected $simplApi;

    /**
     * @param PageFactory $resultPageFactory
     * @param Json $json
     * @param Http $http
     * @param RequestInterface $request
     * @param SimplApi $simplApi
     * @param Logger $logger
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Json $json,
        Http $http,
        RequestInterface $request,
        SimplApi $simplApi,
        Logger $logger
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
     */
    public function execute()
    {
        try {

            $secret = $this->request->getParam('client_secret');
            $clientId = $this->request->getParam('client_id');
            $response = $this->simplApi->install($secret, $clientId);
        } catch (LocalizedException $e) {

            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            $response = ['status'=> false, 'message' => 'Invalid Secret Key'];
        } catch (\Exception $e) {

            $this->logger->error($e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            $response = ['status'=> false, 'message' => 'Error In Registering Secret Key'];
        }

        return $this->jsonResponse($response);
    }

    /**
     * Create json response
     *
     * @param array|string $response
     * @return Http|HttpInterface
     */
    private function jsonResponse($response = '')
    {

        $this->http->getHeaders()->clearHeaders();
        $this->http->setHeader('Content-Type', 'application/json');
        return $this->http->setBody(
            $this->serializer->serialize($response)
        );
    }
}
