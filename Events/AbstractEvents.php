<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;

class AbstractEvents {

    protected $request;

    protected $json;

    public function __construct(
        Http $request,
        Json $json
    ){
        $this->request = $request;
        $this->json = $json;
    }

    /**
     * For preparing fingerprint data
     * @return array
     */
    public function getFingerprint()
    {

        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $data["fingerprint"]["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
        }
        if (isset($_SERVER["REMOTE_ADDR"])) {
            $data["fingerprint"]["user_ip"] = $_SERVER["REMOTE_ADDR"];
        }
        if (isset($_SERVER["HTTP_HOST"]) and isset($_SERVER["REQUEST_URI"])) {
            $data["page_url"] = $this->getParam("page_url");
        }

        return $data;
    }

    /**
     * @param $key
     */
    public function getParam($key)
    {
        $content = $this->json->unserialize($this->request->getContent());
        if (isset($content[$key])) {
            return $content[$key];
        }
        return null;
    }
}
