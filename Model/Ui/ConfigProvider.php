<?php

namespace Simpl\Checkout\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Simpl\Checkout\Helper\Config;

class ConfigProvider implements ConfigProviderInterface
{

    const CODE = 'simplcheckout';

    protected $config;

    /**
     * @param \Simpl\Checkout\Helper\Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @return string[][][]
     */
    public function getConfig()
    {
        $data = [];

        $data = [
            'payment' => [
                self::CODE => [
                    'instructions' => $this->config->getInstructions(),
                    'title_for_frontend' => $this->config->getTitleForFrontend(),
                    'button_label' => $this->config->getButtonLabel()
                ]
            ]
        ];

        return $data;
    }
}
