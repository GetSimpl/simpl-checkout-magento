<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\AppliedChargesDataInterface;

class AppliedChargesData implements AppliedChargesDataInterface
{
    private $title;
    private $description;
    private $chargeType;
    private $amount;

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @inheritDoc
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getChargeType()
    {
        return $this->chargeType;
    }

    /**
     * @inheritDoc
     */
    public function setChargeType($chargeType)
    {
        $this->chargeType = $chargeType;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @inheritDoc
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
}
