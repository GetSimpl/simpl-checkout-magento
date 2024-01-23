<?php

namespace Simpl\Checkout\Api\Data;

interface AppliedDiscountsDataInterface
{
    /**
     * Get discount title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set discount title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get discount description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set discount description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get discount type
     *
     * @return string|null
     */
    public function getDiscountType();

    /**
     * Set discount type
     *
     * @param string $discountType
     * @return $this
     */
    public function setDiscountType($discountType);

    /**
     * Get discount campaign ID
     *
     * @return string|null
     */
    public function getDiscountCampaignId();

    /**
     * Set discount campaign ID
     *
     * @param string $discountCampaignId
     * @return $this
     */
    public function setDiscountCampaignId($discountCampaignId);

    /**
     * Get discount amount in paise
     *
     * @return string|null
     */
    public function getDiscountAmountInPaise();

    /**
     * Set discount amount in paise
     *
     * @param string $discountAmount
     * @return $this
     */
    public function setDiscountAmountInPaise($discountAmount);
}
