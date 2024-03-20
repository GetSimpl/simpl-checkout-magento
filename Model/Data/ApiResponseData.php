<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\ApiResponseDataInterface;
use Magento\Framework\Webapi\Rest\Response;

class ApiResponseData implements ApiResponseDataInterface
{
    /**
     * @var mixed
     */
    private $success;
    /**
     * @var mixed
     */
    private $version;
    /**
     * @var mixed
     */
    private $error;
    /**
     * @var mixed
     */
    private $data;
    /**
     * @var string
     */
    public const VERSION = '1.0.0';
    /**
     * @var mixed
     */
    protected $response;

    /**
     * @param Response $response
     */
    public function __construct(
        Response $response
    ) {
        $this->response = $response;
        $this->success = false;
    }

    /**
     * @inheritDoc
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @inheritDoc
     */
    public function setSuccess($success)
    {
        $this->success = $success;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVersion()
    {
        if ($this->version) {
            return $this->version;
        }
        return self::VERSION;
    }

    /**
     * @inheritDoc
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @inheritDoc
     */
    public function setError($error)
    {
        $this->error = $error;
        $this->response->setStatusHeader(500, '1.1', 'Server Error');
        $this->response->setHeader('Status', '500 Server Error');
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
