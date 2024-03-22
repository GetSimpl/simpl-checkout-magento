<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\ApiDataInterface;
use Simpl\Checkout\Api\Data\MessageDataInterface;

class RefundConfirmResponse
{

    /**
     * @var ErrorDataInterface
     */
    protected $errorData;

    /**
     * @var ApiDataInterface
     */
    protected $apiData;

    protected $messageData;

    public function __construct(
        ApiDataInterface $apiData,
        ErrorDataInterface $errorData,
        MessageDataInterface $messageData
    ) {
        $this->apiData = $apiData;
        $this->errorData = $errorData;
        $this->messageData = $messageData;
    }

    /**
     * @return ApiDataInterface
     */
    public function errorMessage()
    {
        $this->errorData->setCode('validation_failed');
        $this->errorData->setMessage('Validation failed');
        $this->apiData->setError($this->errorData);
        return $this->apiData;
    }

    /**
     * @param $code
     * @param $message
     * @return ApiDataInterface
     */
    public function setError($code, $message)
    {

        $this->errorData->setCode($code);
        $this->errorData->setMessage($message);
        $this->apiData->setError($this->errorData);
        return $this->apiData;
    }

    /**
     * @param $message
     * @return ApiDataInterface
     */
    public function setMessage($message)
    {

        $this->apiData->setSuccess(true);
        $this->messageData->setMessage($message);
        $this->apiData->setData($this->messageData);
        return $this->apiData;
    }
}
