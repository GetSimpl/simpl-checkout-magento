<?php

namespace Simpl\Checkout\Helper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;

class Alert extends AbstractHelper
{
    /**
     * ALERT API URL
     */
    public const ALERT_API = 'api/v1/magento/alert/track';

    /**
     * @var GuzzleHttpClient
     */
    protected $client;

    /**
     * @var Json
     */
    protected $json;
    /**
     * @var Config
     */
    protected $config;
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var AuthHelper
     */
    protected $authHelper;
    /**
     * @var Http
     */
    protected $httpClient;

    /**
     * @param GuzzleHttpClient $client
     * @param Json $json
     * @param AuthHelper $authHelper
     * @param Config $config
     * @param UrlInterface $url
     * @param Context $context
     * @param Http $httpClient
     */
    public function __construct(
        GuzzleHttpClient $client,
        Json $json,
        AuthHelper $authHelper,
        Config $config,
        UrlInterface $url,
        Context $context,
        Http $httpClient
    ) {
        $this->client = $client;
        $this->json = $json;
        $this->config = $config;
        $this->url = $url;
        $this->authHelper = $authHelper;
        $this->httpClient = $httpClient;
        parent::__construct($context);
    }

    /**
     * This function prepare the payload for sending alerts
     *
     * @param string $message
     * @param string $type
     * @param string|null $stacktrace
     * @return bool
     */
    public function alert($message, $type, $stacktrace = null)
    {
        $data["error"]["message"] = $message;
        $data["error"]["level"] = $type;
        $data["error"]["stacktrace"] = $stacktrace;
        $data["merchant_details"] = [
            "domain" => $this->config->getDomain(),
            "client_id" => $this->config->getClientId()
        ];
        $data["current_url"] = $this->url->getCurrentUrl();
        $data["environment"] = $this->config->getIntegrationMode();
        $data["extension_version"] = $this->config->getVersion();
        $data["device"]["name"] = $this->httpClient->getServer()->get('HTTP_USER_AGENT');
        $data["device"]["ip"] = $this->httpClient->getServer()->get('REMOTE_ADDR');

        return $this->postRequest($data);
    }

    /**
     * For sending post request to simpl
     *
     * @param array $body
     * @return bool
     * @throws GuzzleException
     */
    private function postRequest(array $body)
    {
        $hostUrl = $this->config->getApiUrl();
        $requestUrl = $hostUrl.self::ALERT_API;
        $body = $this->json->serialize($body);

        try {
            $header = $this->getHeaders();

            $this->client->request(
                'POST',
                $requestUrl,
                [
                    'headers' => $header,
                    'body' => $body
                ]
            );
            return true;

        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Function to prepare header
     *
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    private function getHeaders()
    {
        $clientId = $this->authHelper->getClientId();
        $nonce = $this->authHelper->generateUuid();
        $signature = $this->authHelper->generateSignature($nonce);
        $domain = $this->config->getDomain();

        return [
            'SIMPL-CLIENT-ID' => $clientId,
            'SIMPL-CLIENT-NONCE' => $nonce,
            'SIMPL-CLIENT-SIGNATURE' => $signature,
            'SHOP-DOMAIN' => $domain,
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json'
        ];
    }
}
