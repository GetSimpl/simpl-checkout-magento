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
     * Check if the response is success or not
     *
     * @return bool
     */
    public function isSuccess()
    {
        if (isset($this->data["success"]) && $this->data["success"] == true) {
            return true;
        }
        return false;
    }

    /**
     * To retrieve the response data
     *
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
     * Check if the response have any error or not
     *
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
