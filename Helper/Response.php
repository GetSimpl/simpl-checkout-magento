<?php

namespace Simpl\Checkout\Helper;

class Response
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(
        array $data
    ) {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        if (isset($this->data["success"]) and $this->data["success"] == true) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->data["data"])) {
            return $this->data["data"];
        }
        return [];
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        if (isset($this->data["error"]["message"])) {
            return $this->data["error"]["message"];
        }
        return "Error in response";
    }
}
