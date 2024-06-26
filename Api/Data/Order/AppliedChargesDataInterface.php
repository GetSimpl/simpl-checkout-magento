<?php

namespace Simpl\Checkout\Api\Data\Order;

interface AppliedChargesDataInterface
{
    /**
     * Get charge title
     *
     * @return string|null
     */
    public function getTitle();

    /**
     * Set charge title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Get charge description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Set charge description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get charge type
     *
     * @return string|null
     */
    public function getChargeType();

    /**
     * Set charge type
     *
     * @param string $chargeType
     * @return $this
     */
    public function setChargeType($chargeType);

    /**
     * Get charges amount in paise
     *
     * @return string|null
     */
    public function getAmount();

    /**
     * Set charges amount in paise
     *
     * @param string $amount
     * @return $this
     */
    public function setAmount($amount);
}
