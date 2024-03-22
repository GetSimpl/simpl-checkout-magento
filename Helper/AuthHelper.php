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
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $secret;

    /**
     * @param Config $config
     * @param Context $context
     */
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
     *  To get the client id
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * To get the client secret
     *
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * For setting the client id
     *
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    /**
     * For setting the secret
     *
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * This will validate the signature and return true if it's valid
     *
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
     * For generating the signature based on client id and secret
     *
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
     * For generating the UUID
     *
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
