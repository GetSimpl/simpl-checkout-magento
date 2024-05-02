<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\PaymentDataInterface;

class PaymentData implements PaymentDataInterface
{
    /**
     * @var mixed
     */
    private $status;
    /**
     * @var mixed
     */
    private $mode;
    /**
     * @var mixed
     */
    private $method;
    /**
     * @var mixed
     */
    private $grandTotal;
    /**
     * @var mixed
     */
    private $totalPaid;

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

    /**
     * @inheritDoc
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * @inheritDoc
     */
    public function setGrandTotal($grandTotal)
    {
        $this->grandTotal = $grandTotal;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTotalPaid()
    {
        return $this->totalPaid;
    }

    /**
     * @inheritDoc
     */
    public function setTotalPaid($totalPaid)
    {
        $this->totalPaid = $totalPaid;
        return $this;
    }
}
