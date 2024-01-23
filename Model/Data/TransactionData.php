<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\TransactionDataInterface;

class TransactionData implements TransactionDataInterface
{
    private $id;
    private $parentId;
    private $type;
    private $closed;

    /**
     * @inheritDoc
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @inheritDoc
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getParentId() {
        return $this->parentId;
    }

    /**
     * @inheritDoc
     */
    public function setParentId($parentId) {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isClosed() {
        return $this->closed;
    }

    /**
     * @inheritDoc
     */
    public function setClosed($closed) {
        $this->closed = $closed;
        return $this;
    }
}
