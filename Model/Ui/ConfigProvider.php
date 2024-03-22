<?php

namespace Simpl\Checkout\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Simpl\Checkout\Helper\Config;

class ConfigProvider implements ConfigProviderInterface
{
    public const CODE = 'simplcheckout';

    /**
     * @var Config
     */
    protected $config;

    /**
     * ConfigProvider constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Get configuration data.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'payment' => [
                self::CODE => [
                    'instructions' => $this->config->getInstructions(),
                    'title_for_frontend' => $this->config->getTitleForFrontend(),
                    'button_label' => $this->config->getButtonLabel()
                ]
            ]
        ];
    }
}
