<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\ErrorDataInterface;
use Simpl\Checkout\Api\Data\ApiDataInterface;
use Simpl\Checkout\Api\Data\MessageDataInterface;
use Simpl\Checkout\Api\Data\CreditMemoDataInterface;

class GetCreditmemoResponse
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
     * @var CreditMemoDataInterface
     */
    protected $creditMemoData;

    /**
     * @param ApiDataInterface $apiData
     * @param ErrorDataInterface $errorData
     * @param MessageDataInterface $messageData
     * @param CreditMemoDataInterface $creditMemoData
     */
    public function __construct(
        ApiDataInterface $apiData,
        ErrorDataInterface $errorData,
        MessageDataInterface $messageData,
        CreditMemoDataInterface $creditMemoData
    ) {
        $this->apiData = $apiData;
        $this->errorData = $errorData;
        $this->messageData = $messageData;
        $this->creditMemoData = $creditMemoData;
    }

    /**
     * Sets message in the API response data.
     *
     * @param string $message
     * @return ApiDataInterface
     */
    public function setMessage($message)
    {
        $this->apiData->setSuccess(true);
        $this->messageData->setMessage($message);
        $this->apiData->setData($this->messageData);
        return $this->apiData;
    }

    /**
     * Sets credit memo in the API response data.
     *
     * @param \Magento\Sales\Api\Data\CreditmemoInterface $creditMemo
     * @return CreditMemoDataInterface
     */
    public function setCreditMemo($creditMemo)
    {
        $this->creditMemoData->setSuccess(true);
        $this->creditMemoData->setData($creditMemo);
        return $this->creditMemoData;
    }

    /**
     * Sets error details in the API response data.
     *
     * @return CreditMemoDataInterface
     */
    public function creditMemoNotFoundError()
    {
        $this->creditMemoData->setSuccess(false);
        $this->errorData->setCode("creditmemo_not_found");
        $this->errorData->setMessage("Credit Memo not found");
        $this->creditMemoData->setError($this->errorData);
        return $this->creditMemoData;
    }
}
