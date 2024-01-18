<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    const SCOPE = 'website';
    const SIMPL_PAYMENT_CODE = 'simplcheckout';
    const SIMPL_PAYMENT_ACTIVE = 'active';
    const SIMPL_PAYMENT_MODE = 'mode';
    const SIMPL_PAYMENT_CLIENT_ID = 'client_id';
    const SIMPL_PAYMENT_TEST_SECRET = 'test_secret';
    const SIMPL_PAYMENT_LIVE_SECRET = 'live_secret';
    const SIMPL_PAYMENT_TITLE = 'title';
    const SIMPL_PAYMENT_TITLE_FRONTEND = 'title_for_frontend';
    const SIMPL_PAYMENT_INS = 'instructions';
    const SIMPL_LIVE_HOST_URL = 'LIVE_URL_HERE';
    const SIMPL_TEST_HOST_URL = 'TEST_URL_HERE';

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * Check if Simpl Checkout is Enabled
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->getSimplConfig(self::SIMPL_PAYMENT_ACTIVE);
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getApiUrl() {
        if($this->isLiveIntegration()) {
            return $this->getSimplConfig(self::SIMPL_LIVE_HOST_URL);
        }
        return $this->getSimplConfig(self::SIMPL_TEST_HOST_URL);
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getIntegrationMode() {
        return $this->getSimplConfig(self::SIMPL_PAYMENT_MODE);
    }

    /**
     * Get Client Id
     *
     * @return string
     */
    public function getClientId() {
        return $this->getSimplConfig(self::SIMPL_PAYMENT_CLIENT_ID);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle() {
        return $this->getSimplConfig(self::SIMPL_PAYMENT_TITLE);
    }

    /**
     * Get Instructions
     *
     * @return string
     */
    public function getInstructions() {
        return $this->getSimplConfig(self::SIMPL_PAYMENT_INS);
    }

    /**
     * Get Title for frontend
     *
     * @return string
     */
    public function getTitleForFrontend() {
        return $this->getSimplConfig(self::SIMPL_PAYMENT_TITLE_FRONTEND);
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getSecret() {
        if ($this->isLiveIntegration()) {
            return $this->getSimplConfig(self::SIMPL_PAYMENT_LIVE_SECRET);
        }
        return $this->getSimplConfig(self::SIMPL_PAYMENT_TEST_SECRET);
    }

    /**
     * @param $key
     * @return string
     */
    private function getSimplConfig($key) {
        $configKey = 'payment/' . self::SIMPL_PAYMENT_CODE . '/' .$key;
        return (string) $this->scopeConfig->getValue(
            $configKey,
            self::SCOPE
        );
    }

    /**
     * @return bool
     */
    private function isLiveIntegration() {
        return $this->getIntegrationMode() == 'live';
    }

}
