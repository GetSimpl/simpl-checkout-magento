<?php

namespace Simpl\Checkout\Controller\Payment;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Simpl\Checkout\Model\Simpl;

class Init implements HttpGetActionInterface
{

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    private $simpl;

    /**
     * JsonResponse constructor.
     *
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        JsonFactory $jsonFactory,
        Simpl $simpl
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->simpl = $simpl;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data = ['status' => 'error'];
        $url = $this->simpl->init();
        if ($url) {
            $data = ['url' => $url, 'status' => 'success'];
        }
        $resultJson = $this->jsonFactory->create();
        $resultJson->setData($data);
        return $resultJson;
    }
}
