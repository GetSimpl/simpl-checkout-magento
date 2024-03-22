<?php

namespace Simpl\Checkout\Events;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;

class AbstractEvents
{
    /**
     * @var Http
     */
    protected $request;
    /**
     * @var Json
     */
    protected $json;

    /**
     * @param Http $request
     * @param Json $json
     */
    public function __construct(
        Http $request,
        Json $json
    ) {
        $this->request = $request;
        $this->json = $json;
    }

    /**
     * For preparing fingerprint data
     *
     * @return array
     */
    public function getFingerprint()
    {
        if ($userAgent = $this->request->getServer()->get('HTTP_USER_AGENT')) {
            $data["fingerprint"]["user_agent"] = $userAgent;
        }
        if ($userIp = $this->request->getServer()->get('REMOTE_ADDR')) {
            $data["fingerprint"]["user_ip"] = $userIp;
        }
        if ($pageUrl = $this->getParam("page_url")) {
            $data["page_url"] = $pageUrl;
        }

        return $data;
    }

    /**
     * To retrieve json body param based on key
     *
     * @param string $key
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
