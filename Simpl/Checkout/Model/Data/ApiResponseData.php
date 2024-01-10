<?php

namespace Simpl\Checkout\Model\Data;

class ApiResponseData implements \Simpl\Checkout\Api\Data\ApiResponseDataInterface
{
    private $success;
    private $version;
    private $error;

    const VERSION = '1.0.0';

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
}
