<?php

namespace Simpl\Checkout\Model\Data;

class AppliedChargesData implements \Simpl\Checkout\Api\Data\AppliedChargesDataInterface
{
    private $title;
    private $description;
    private $chargeType;
    private $chargesAmountInPaise;

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
    public function getChargesAmountInPaise()
    {
        return $this->chargesAmountInPaise;
    }

    /**
     * @inheritDoc
     */
    public function setChargesAmountInPaise($chargesAmount)
    {
        $this->chargesAmountInPaise = $chargesAmount;
        return $this;
    }
}
