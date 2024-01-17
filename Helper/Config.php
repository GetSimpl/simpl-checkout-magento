<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Config extends AbstractHelper
{
    const SCOPE = 'website';
    const SIMPL_PAYMENT_ACTIVE = 'payment/simplcheckout/active';
    const SIMPL_PAYMENT_MODE = 'payment/simplcheckout/mode';
    const SIMPL_PAYMENT_CLIENT_ID = 'payment/simplcheckout/client_id';
    const SIMPL_PAYMENT_TEST_SECRET = 'payment/simplcheckout/test_secret';
    const SIMPL_PAYMENT_LIVE_SECRET = 'payment/simplcheckout/live_secret';
    const SIMPL_PAYMENT_TITLE = 'payment/simplcheckout/title';
    const SIMPL_PAYMENT_TITLE_FRONTEND = 'payment/simplcheckout/title_for_frontend';
    const SIMPL_PAYMENT_INS = 'payment/simplcheckout/instructions';
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
        return (bool)$this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_ACTIVE,
            self::SCOPE
        );
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getApiUrl() {
        $mode = $this->getSimplMode();
        if($mode == 'live') {
            return (string) self::POST_URL_LIVE;
        }
        return (string) self::POST_URL_TEST;
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getSimplMode() {
        return (string) $this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_MODE,
            self::SCOPE
        );
    }

    /**
     * Get Client Id
     *
     * @return string
     */
    public function getClientId() {
        return (string) $this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_CLIENT_ID,
            self::SCOPE
        );
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle(): string {
        return (string) $this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_TITLE,
            self::SCOPE
        );
    }

    /**
     * Get Instructions
     *
     * @return string
     */
    public function getInstructions() {
        return (string) $this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_INS,
            self::SCOPE
        );
    }

    /**
     * Get Title for frontend
     *
     * @return string
     */
    public function getTitleForFrontend() {
        return (string) $this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_TITLE_FRONTEND,
            self::SCOPE
        );
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getSecret() {
        $mode = $this->getSimplMode();
        if ($mode == 'live') {
            return (string) $this->scopeConfig->getValue(
                self::SIMPL_PAYMENT_LIVE_SECRET,
                self::SCOPE
            );
        }
        return (string) $this->scopeConfig->getValue(
            self::SIMPL_PAYMENT_TEST_SECRET,
            self::SCOPE
        );
    }

}
