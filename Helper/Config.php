<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Helper\Context;

class Config extends AbstractHelper
{

    public const SCOPE = 'website';
    public const VERSION = '1.0.0';
    public const KEY_PAYMENT_CODE = 'simplcheckout';
    public const KEY_VIRTUAL_PRODUCT_ACTIVE = 'virtual_product';
    public const KEY_CHECKOUT_ACTIVE = 'active';
    public const KEY_LOG = 'logging';
    public const KEY_MODE = 'mode';
    public const KEY_CLIENT_ID = 'client_id';
    public const KEY_TEST_SECRET = 'test_secret';
    public const KEY_LIVE_SECRET = 'live_secret';
    public const KEY_TITLE = 'title';
    public const KEY_ALLOWED_EMAILS = 'allowed_emails';
    public const KEY_BUTTON_LABEL = 'place_order_button_label';
    public const KEY_ORDER_STATUS = 'order_status';
    public const KEY_TITLE_FRONTEND = 'title_for_frontend';
    public const KEY_PAYMENT_INS = 'instructions';
    public const KEY_SIMPL_LIVE_HOST_URL = 'https://checkout-platform-integrations.getsimpl.com/';
    public const KEY_SIMPL_TEST_HOST_URL = 'https://sandbox-checkout-platform-integrations.getsimpl.com/';
    public const ABANDONED_ORDER_LIFE_TIME = 60;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Check if Simpl Checkout is Enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->getSimplConfig(self::KEY_CHECKOUT_ACTIVE);
    }

    /**
     * Check if Virtual Product is enabled
     *
     * @return bool
     */
    public function isVirtualProductEnabled()
    {
        return $this->getSimplConfig(self::KEY_VIRTUAL_PRODUCT_ACTIVE);
    }

    /**
     * Check if logging is enabled
     *
     * @return bool
     */
    public function isLogEnabled()
    {
        return $this->getSimplConfig(self::KEY_LOG);
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getApiUrl()
    {
        if ($this->isLiveIntegration()) {
            return self::KEY_SIMPL_LIVE_HOST_URL;
        }
        return self::KEY_SIMPL_TEST_HOST_URL;
    }

    /**
     * Get Mode
     *
     * @return string
     */
    public function getIntegrationMode()
    {
        return $this->getSimplConfig(self::KEY_MODE);
    }

    /**
     * Get New Order Status
     *
     * @return string
     */
    public function getNewOrderStatus()
    {
        return $this->getSimplConfig(self::KEY_ORDER_STATUS);
    }

    /**
     * Get Client Id
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->getSimplConfig(self::KEY_CLIENT_ID);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getSimplConfig(self::KEY_TITLE);
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function getAllowedEmails()
    {
        return $this->getSimplConfig(self::KEY_ALLOWED_EMAILS);
    }

    /**
     * To get place order button title
     *
     * @return string
     */
    public function getButtonLabel()
    {
        return $this->getSimplConfig(self::KEY_BUTTON_LABEL);
    }

    /**
     * Get Instructions
     *
     * @return string
     */
    public function getInstructions()
    {
        return $this->getSimplConfig(self::KEY_PAYMENT_INS);
    }

    /**
     * Get Title for frontend
     *
     * @return string
     */
    public function getTitleForFrontend()
    {
        return $this->getSimplConfig(self::KEY_TITLE_FRONTEND);
    }

    /**
     * Get API Url
     *
     * @return string
     */
    public function getSecret()
    {
        if ($this->isLiveIntegration()) {
            return $this->getSimplConfig(self::KEY_LIVE_SECRET);
        }
        return $this->getSimplConfig(self::KEY_TEST_SECRET);
    }

    /**
     * Get Magento configuration based on key
     *
     * @param string $key
     * @return string
     */
    private function getSimplConfig($key)
    {
        $configKey = 'payment/' . self::KEY_PAYMENT_CODE . '/' .$key;
        return (string) $this->scopeConfig->getValue(
            $configKey,
            self::SCOPE
        );
    }

    /**
     * Check if integration is live or test
     *
     * @return bool
     */
    private function isLiveIntegration()
    {
        return $this->getIntegrationMode() == 'live';
    }

    /**
     * To retrieve the current domain name of the store
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getDomain()
    {
        $url = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB);
        $parsedUrl = parse_url($url);
        return $parsedUrl['host'] ?? '';
    }

    /**
     * To get module version
     *
     * @return string
     */
    public function getVersion()
    {
        return self::VERSION;
    }
}
