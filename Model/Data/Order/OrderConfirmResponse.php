<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface;
use Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface;
use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\ApiDataInterface;
use Simpl\Checkout\Api\Data\MessageDataInterface;

class OrderConfirmResponse
{
    /**
     * @var ApiDataInterface
     */
    protected $apiData;
    /**
     * @var ErrorDataInterface
     */
    protected $errorData;

    /**
     * @var MessageDataInterface
     */
    protected $messageData;

    /**
     * @var RedirectionUrlDataInterface
     */
    protected $redirectionUrlData;

    /**
     * @var OrderConfirmSuccessDataInterface
     */
    protected $orderConfirmSuccessData;

    /**
     * @param ApiDataInterface $apiData
     * @param ErrorDataInterface $errorData
     * @param MessageDataInterface $messageData
     * @param RedirectionUrlDataInterface $redirectionUrlData
     * @param OrderConfirmSuccessDataInterface $orderConfirmSuccessData
     */
    public function __construct(
        ApiDataInterface $apiData,
        ErrorDataInterface $errorData,
        MessageDataInterface $messageData,
        RedirectionUrlDataInterface $redirectionUrlData,
        OrderConfirmSuccessDataInterface $orderConfirmSuccessData
    ) {
        $this->apiData = $apiData;
        $this->errorData = $errorData;
        $this->messageData = $messageData;
        $this->redirectionUrlData = $redirectionUrlData;
        $this->orderConfirmSuccessData = $orderConfirmSuccessData;
    }

    /**
     * @param $url
     * @return OrderConfirmSuccessDataInterface
     */
    public function setRedirectionURL($url) {

        $this->orderConfirmSuccessData->setSuccess(true);
        $this->redirectionUrlData->setRedirectionUrl($url);
        $this->orderConfirmSuccessData->setData($this->redirectionUrlData);
        return $this->orderConfirmSuccessData;
    }

    /**
     * @param $code
     * @param $message
     * @return OrderConfirmSuccessDataInterface
     */
    public function setError($code, $message) {

        $this->orderConfirmSuccessData->setSuccess(false);
        $this->errorData->setCode($code);
        $this->errorData->setMessage($message);
        $this->orderConfirmSuccessData->setError($this->errorData);
        return $this->orderConfirmSuccessData;
    }

    /**
     * @param $message
     * @return ApiDataInterface
     */
    public function setMessage($message) {

        $this->apiData->setSuccess(true);
        $this->messageData->setMessage($message);
        $this->apiData->setData($this->messageData);
        return $this->apiData;
    }
}
