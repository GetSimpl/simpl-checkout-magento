<?php

namespace Simpl\Checkout\Api\Data\Order;

interface PaymentDataInterface
{
    /**
     * Get status
     *
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get mode
     *
     * @return string|null
     */
    public function getMode();

    /**
     * Set mode
     *
     * @param string $mode
     * @return $this
     */
    public function setMode($mode);

    /**
     * Get method
     *
     * @return string|null
     */
    public function getMethod();

    /**
     * Set method
     *
     * @param string $method
     * @return $this
     */
    public function setMethod($method);

    /**
     * Get method
     *
     * @return string|null
     */
    public function getGrandTotal();

    /**
     * Set method
     *
     * @param string $grandTotal
     * @return $this
     */
    public function setGrandTotal($grandTotal);

    /**
     * Get method
     *
     * @return string|null
     */
    public function getTotalPaid();

    /**
     * Set method
     *
     * @param string $totalPaid
     * @return $this
     */
    public function setTotalPaid($totalPaid);
}
