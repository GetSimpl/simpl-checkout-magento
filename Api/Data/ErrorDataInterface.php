<?php

namespace Simpl\Checkout\Api\Data;

interface ErrorDataInterface
{
    /**
     * Get the code.
     *
     * @return string|null
     */
    public function getCode();

    /**
     * Set the code.
     *
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * Get the message.
     *
     * @return string|null
     */
    public function getMessage();

    /**
     * Set the message.
     *
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
}
