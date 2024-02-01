<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;

class PaymentData implements PaymentDataInterface
{
    private $status;
    private $mode;
    private $method;

    /**
     * @inheritDoc
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @inheritDoc
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @inheritDoc
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }
}
