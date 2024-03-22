<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class AuthHelper extends AbstractHelper
{
    /**
     * @var Config
     */
    protected $config;

    private $clientId;

    private $secret;

    public function __construct(
        Config $config,
        Context $context
    ) {
        $this->config = $config;
        $this->setClientId($this->config->getClientId());
        $this->setSecret($this->config->getSecret());
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * @param $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param string $clientId
     * @param string $nonce
     * @param string $signature
     * @return bool
     * @throws \Exception
     */
    public function validateSignature(string $clientId, string  $nonce, string  $signature)
    {
        $localSignature = $this->generateSignature($nonce, $clientId);
        return $signature == $localSignature;
    }

    /**
     * @param string $nonce
     * @param string|null $clientId
     * @return false|string
     */
    public function generateSignature(string $nonce, string $clientId = null)
    {

        if (!$clientId) {
            $clientId = $this->getClientId();
        }

        $clientSecret = $this->getSecret();
        $data = $nonce . "-" . $clientId;

        return hash_hmac('sha1', $data, $clientSecret);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateUuid()
    {
        $b = random_bytes(16);
        $b[6] = chr(ord($b[6]) & 0x0f | 0x40);
        $b[8] = chr(ord($b[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
}
