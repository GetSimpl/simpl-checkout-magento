<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\AppliedDiscountsDataInterface;

class AppliedDiscountsData implements AppliedDiscountsDataInterface
{
    private $title;
    private $description;
    private $discountType;
    private $discountCampaignId;
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
    public function getDiscountType()
    {
        return $this->discountType;
    }

    /**
     * @inheritDoc
     */
    public function setDiscountType($discountType)
    {
        $this->discountType = $discountType;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDiscountCampaignId()
    {
        return $this->discountCampaignId;
    }

    /**
     * @inheritDoc
     */
    public function setDiscountCampaignId($discountCampaignId)
    {
        $this->discountCampaignId = $discountCampaignId;
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
