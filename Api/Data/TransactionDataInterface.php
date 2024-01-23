<?php

namespace Simpl\Checkout\Api\Data;

interface TransactionDataInterface
{
    /**
     * Get transaction ID
     *
     * @return string|null
     */
    public function getId();

    /**
     * Set transaction ID
     *
     * @param string $id
     * @return $this
     */
    public function setId($id);

    /**
     * Get parent ID
     *
     * @return string|null
     */
    public function getParentId();

    /**
     * Set parent ID
     *
     * @param string $parentId
     * @return $this
     */
    public function setParentId($parentId);

    /**
     * Get transaction type
     *
     * @return string|null
     */
    public function getType();

    /**
     * Set transaction type
     *
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * Check if transaction is closed
     *
     * @return bool|null
     */
    public function isClosed();

    /**
     * Set transaction closure status
     *
     * @param bool $closed
     * @return $this
     */
    public function setClosed($closed);
}
