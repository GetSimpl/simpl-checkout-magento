<?php

namespace Simpl\Checkout\Model\Plugin;

use Magento\Framework\Authorization;
use Closure;
use Simpl\Checkout\Helper\AuthHelper;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Simpl\Checkout\Logger\Logger;

class SimplAuthorization
{
    /**
     * @var AuthHelper
     */
    protected $authHelper;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(
        AuthHelper $authHelper,
        Request $request,
        Logger $logger
    ) {
        $this->authHelper = $authHelper;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * Check if resource for which access is needed has simpl permissions defined in webapi config.
     *
     * For testing
     * $clientId 123 secret 456
     * $nonce cbc19723-c8e7-4c8e-9508-185998e4d8dd
     * $signature 0dee7c08f3aa694bd0db5687bee846dc3a47ba01
     *
     * @param Authorization $subject
     * @param Closure $proceed
     * @param string $resource
     * @param string $privilege
     * @return bool true If resource permission is simpl,
     * to allow any user access without further checks in parent method
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundIsAllowed(
        Authorization $subject,
        Closure       $proceed,
        $resource,
        $privilege = null
    ) {
        if ($resource == 'simpl') {
            // To get header data
            $clientId = $this->request->getHeader('SIMPL-CLIENT-ID');
            $nonce = $this->request->getHeader('SIMPL-CLIENT-NONCE');
            $signature = $this->request->getHeader('SIMPL-CLIENT-SIGNATURE');

            try {
                return $this->authHelper->validateSignature($clientId, $nonce, $signature);
            } catch (\Exception $e) {
                $this->logger->error('Exception in API Authorization: ' . $e->getMessage());
                return $proceed($resource, $privilege);
            }
        } else {
            return $proceed($resource, $privilege);
        }
    }
}
