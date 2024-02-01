<?php

namespace Simpl\Checkout\Api\Data;

interface MessageDataInterface
{
    /**
     * Get message
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Set message
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
}
