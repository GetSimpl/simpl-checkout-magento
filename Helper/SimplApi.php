<?php

namespace Simpl\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class SimplApi extends AbstractHelper {

    const INSTALL_API = 'api/v1/mogento/app/install';

    /**
     * @var SimplClient
     */
    protected $simplClient;

    public function __construct(
        SimplClient $simplClient,
        Context $context
    )
    {
        $this->simplClient = $simplClient;
        parent::__construct($context);
    }

    /**
     * API to install plugin
     * @param string $secret
     * @param string $clientId
     * @return array
     */
    public function install(string $secret, string $clientId) {
        $this->simplClient->setClientId($clientId);
        $this->simplClient->setSecret($secret);
        $response = $this->simplClient->callSimplApi(self::INSTALL_API);
        if ($response->getSuccess()) {
            return [
                'status' => true,
                'message' => 'Congratulations! Valid Credentials'
            ];
        }
        return [
            'status' => false,
            'message' => $response->getError()->getMessage()
        ];
    }
}
