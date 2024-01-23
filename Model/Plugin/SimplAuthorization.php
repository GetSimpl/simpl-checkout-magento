<?php

namespace Simpl\Checkout\Model\Plugin;

use Magento\Integration\Api\AuthorizationServiceInterface as AuthorizationService;
use Magento\Framework\Authorization;
use Closure;
use Simpl\Checkout\Helper\SimplClient;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class SimplAuthorization
{
    /**
     * @var SimplClient
     */
    protected $simpl;

    protected $request;

    public function __construct(
        SimplClient $simpl,
        Request $request
    ) {
        $this->simpl = $simpl;
        $this->request = $request;
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
            $nonce = $this->request->getHeader('SIMPL-SERVICE-NONCE');
            $signature = $this->request->getHeader('SIMPL-SERVICE-SIGNATURE');

            return $this->simpl->validateSignature($clientId, $nonce, $signature);

        } else {
            return $proceed($resource, $privilege);
        }
    }
}
