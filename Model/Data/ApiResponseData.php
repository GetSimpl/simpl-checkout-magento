<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\ApiResponseDataInterface;

class ApiResponseData implements \Simpl\Checkout\Api\Data\ApiResponseDataInterface
{
    private $success;
    private $version;
    private $error;
    private $data;

    const VERSION = '1.0.0';

    public function __construct()
    {
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
