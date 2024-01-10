<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\MessageDataInterface;

class MessageData implements \Simpl\Checkout\Api\Data\MessageDataInterface
{
    private $message;

    /**
     * @inheritDoc
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @inheritDoc
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
}
