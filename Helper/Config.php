<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;

class Config extends AbstractHelper {

    const SCOPE = 'website';
    const KEY_PAYMENT_CODE = 'simplcheckout';
    const KEY_CHECKOUT_ACTIVE = 'active';
    const KEY_MODE = 'mode';
    const KEY_CLIENT_ID = 'client_id';
    const KEY_TEST_SECRET = 'test_secret';
    const KEY_LIVE_SECRET = 'live_secret';
    const KEY_TITLE = 'title';
    const KEY_TITLE_FRONTEND = 'title_for_frontend';
    const KEY_PAYMENT_INS = 'instructions';
    const KEY_SIMPL_LIVE_HOST_URL = 'LIVE_URL_HERE';
    const KEY_SIMPL_TEST_HOST_URL = 'TEST_URL_HERE';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Check if Simpl Checkout is Enabled
     *
     * @return bool
     */
    public function isEnabled() {
        return $this->getSimplConfig(self::KEY_CHECKOUT_ACTIVE);
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getApiUrl() {
        if($this->isLiveIntegration()) {
            return $this->getSimplConfig(self::KEY_SIMPL_LIVE_HOST_URL);
        }
        return $this->getSimplConfig(self::KEY_SIMPL_TEST_HOST_URL);
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getIntegrationMode() {
        return $this->getSimplConfig(self::KEY_MODE);
    }

    /**
     * Get Client Id
     *
     * @return string
     */
    public function getClientId() {
        return $this->getSimplConfig(self::KEY_CLIENT_ID);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle() {
        return $this->getSimplConfig(self::KEY_TITLE);
    }

    /**
     * Get Instructions
     *
     * @return string
     */
    public function getInstructions() {
        return $this->getSimplConfig(self::KEY_PAYMENT_INS);
    }

    /**
     * Get Title for frontend
     *
     * @return string
     */
    public function getTitleForFrontend() {
        return $this->getSimplConfig(self::KEY_TITLE_FRONTEND);
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getSecret() {
        if ($this->isLiveIntegration()) {
            return $this->getSimplConfig(self::KEY_LIVE_SECRET);
        }
        return $this->getSimplConfig(self::KEY_TEST_SECRET);
    }

    /**
     * @param $key
     * @return string
     */
    private function getSimplConfig($key) {
        $configKey = 'payment/' . self::KEY_PAYMENT_CODE . '/' .$key;
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

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getDomain() {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }

}
