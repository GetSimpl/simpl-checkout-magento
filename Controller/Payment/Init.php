<?php

namespace Simpl\Checkout\Controller\Payment;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Simpl\Checkout\Model\Simpl\Order;
use Simpl\Checkout\Helper\SimplApi;

class Init implements HttpGetActionInterface
{

    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var SimplApi
     */
    protected $simplApi;

    protected $request;

    /**
     * JsonResponse constructor.
     * @param JsonFactory $jsonFactory
     * @param Order $order
     * @param SimplApi $simplApi
     */
    public function __construct(
        JsonFactory $jsonFactory,
        Order $order,
        SimplApi $simplApi
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->order = $order;
        $this->simplApi = $simplApi;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data = ['status' => 'error'];
        $request = $this->order->getCurrentOrder();
        $redirectionURL = $this->simplApi->initPayment($request);
        if ($redirectionURL) {
            $data = ['url' => $redirectionURL, 'status' => 'success'];
        }
        $resultJson = $this->jsonFactory->create();
        $resultJson->setData($data);
        return $resultJson;
    }
}
