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
    const POST_URL_LIVE = 'LIVE_URL_HERE';
    const POST_URL_TEST = 'TEST_URL_HERE';

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
        $mode = $this->getSimplMode();
        if($mode == 'live') {
            return $this->getSimplConfig(self::POST_URL_LIVE);
        }
        return $this->getSimplConfig(self::POST_URL_TEST);
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getSimplMode() {
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
        $mode = $this->getSimplMode();
        if ($mode == 'live') {
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

}
