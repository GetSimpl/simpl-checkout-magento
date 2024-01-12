<?php

namespace Simpl\Checkout\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;

class ConfigProvider  implements ConfigProviderInterface
{
    const CODE = 'simplcheckout';

    protected $config;

    /**
     * @param \Simpl\Checkout\Helper\Config $config
     */
    public function __construct(
        \Simpl\Checkout\Helper\Config $config
    )
    {
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
                    'title_for_frontend' => $this->config->getTitleForFrontend()
                ]
            ]
        ];

        return $data;
    }
}
