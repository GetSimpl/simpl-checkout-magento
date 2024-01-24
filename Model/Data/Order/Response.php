<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface;
use Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface;
use Simpl\Checkout\Api\Data\ErrorDataInterface;

class Response
{
    /**
     * @var ErrorDataInterface
     */
    protected $errorData;

    /**
     * @var RedirectionUrlDataInterface
     */
    protected $redirectionUrlData;

    /**
     * @var OrderConfirmSuccessDataInterface
     */
    protected $orderConfirmSuccessData;

    /**
     * @param ErrorDataInterface $errorData
     * @param RedirectionUrlDataInterface $redirectionUrlData
     * @param OrderConfirmSuccessDataInterface $orderConfirmSuccessData
     */
    public function __construct(
        ErrorDataInterface $errorData,
        RedirectionUrlDataInterface $redirectionUrlData,
        OrderConfirmSuccessDataInterface $orderConfirmSuccessData
    ) {

        $this->errorData = $errorData;
        $this->redirectionUrlData = $redirectionUrlData;
        $this->orderConfirmSuccessData = $orderConfirmSuccessData;
    }

    /**
     * @param $url
     * @return OrderConfirmSuccessDataInterface
     */
    public function setUrl($url) {

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
}
