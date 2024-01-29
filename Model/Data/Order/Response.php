<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface;
use Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface;
use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\ApiDataInterface;
use Simpl\Checkout\Api\Data\MessageDataInterface;
use Simpl\Checkout\Api\Data\OrderDataInterface;
use Simpl\Checkout\Api\Data\CreditMemoDataInterface;

class Response
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
     * @var OrderDataInterface
     */
    protected $orderData;

    /**
     * @var CreditMemoDataInterface
     */
    protected $creditMemoData;

    /**
     * @param ApiDataInterface $apiData
     * @param ErrorDataInterface $errorData
     * @param MessageDataInterface $messageData
     * @param RedirectionUrlDataInterface $redirectionUrlData
     * @param OrderConfirmSuccessDataInterface $orderConfirmSuccessData
     * @param OrderDataInterface $orderData
     * @param CreditMemoDataInterface $creditMemoData
     */
    public function __construct(
        ApiDataInterface $apiData,
        ErrorDataInterface $errorData,
        MessageDataInterface $messageData,
        RedirectionUrlDataInterface $redirectionUrlData,
        OrderConfirmSuccessDataInterface $orderConfirmSuccessData,
        OrderDataInterface $orderData,
        CreditMemoDataInterface $creditMemoData
    ) {

        $this->apiData = $apiData;
        $this->errorData = $errorData;
        $this->messageData = $messageData;
        $this->redirectionUrlData = $redirectionUrlData;
        $this->orderConfirmSuccessData = $orderConfirmSuccessData;
        $this->orderData = $orderData;
        $this->creditMemoData = $creditMemoData;
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


    /**
     * @param $order
     * @return OrderDataInterface
     */
    public function setOrder($order) {

        $this->orderData->setData($order);
        $this->orderData->setSuccess(true);
        return $this->orderData;
    }

    /**
     * @return OrderDataInterface
     */
    public function orderNotFoundError() {

        $this->orderData->setSuccess(false);
        $this->errorData->setCode("order_not_found");
        $this->errorData->setMessage("Order not found");
        $this->orderData->setError($this->errorData);
        return $this->orderData;
    }

    /**
     * @param $creditMemo
     * @return CreditMemoDataInterface
     */
    public function setCreditMemo($creditMemo) {

        $this->creditMemoData->setSuccess(true);
        $this->creditMemoData->setData($creditMemo);
        return $this->creditMemoData;
    }

    /**
     * @return CreditMemoDataInterface
     */
    public function creditMemoNotFoundError() {

        $this->creditMemoData->setSuccess(false);
        $this->errorData->setCode("creditmemo_not_found");
        $this->errorData->setMessage("Credit Memo not found");
        $this->creditMemoData->setError($this->errorData);
        return $this->creditMemoData;
    }
}
