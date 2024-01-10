<?php

namespace Simpl\Checkout\Model\Data;

class ErrorData implements \Simpl\Checkout\Api\Data\ErrorDataInterface
{
    private $code;
    private $message;

    /**
     * @inheritDoc
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @inheritDoc
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
