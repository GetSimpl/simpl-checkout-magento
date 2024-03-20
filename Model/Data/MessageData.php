<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\MessageDataInterface;

class MessageData implements MessageDataInterface
{
    /**
     * @var mixed
     */
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
