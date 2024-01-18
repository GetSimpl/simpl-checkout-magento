<?php

namespace Simpl\Checkout\Helper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Logger\Logger;
use Magento\Store\Model\StoreManagerInterface;

class SimplClient extends AbstractHelper
{
    /**
     * @var GuzzleHttpClient
     */
    protected $client;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    private $clientId;

    private $secret;

    /**
     * @param GuzzleHttpClient $client
     * @param Json $json
     * @param Config $config
     * @param Logger $logger
     * @param Context $context
     */
    public function __construct(
        GuzzleHttpClient $client,
        Json $json,
        Config $config,
        Logger $logger,
        StoreManagerInterface $storeManager,
        Context $context
    )
    {
        $this->config = $config;
        $this->logger = $logger;
        $this->client = $client;
        $this->json = $json;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * @param string $clientId
     * @param string $nonce
     * @param string $signature
     * @return bool
     * @throws \Exception
     */
    public function validateSignature(string $clientId,string  $nonce,string  $signature) {
        $localSignature = $this->generateSignature($nonce, $clientId);
        return $signature == $localSignature;
    }

    /**
     * @return string
     */
    public function getClientId() {
        if ($this->clientId) {
            return $this->clientId;
        }
        return $this->config->getClientId();
    }

    /**
     * @return string
     */
    public function getSecret() {
        if ($this->secret) {
            return $this->secret;
        }
        return $this->config->getSecret();
    }

    /**
     * @param $clientId
     */
    public function setClientId($clientId) {
        $this->clientId = $clientId;
    }

    /**
     * @param $secret
     */
    public function setSecret($secret) {
        $this->secret = $secret;
    }

    /**
     * Function to prepare header
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    private function getHeaders() {
        $clientId = $this->getClientId();
        $nonce = $this->generateUuid();
        $signature = $this->generateSignature($nonce);
        $domain = $this->getDomain();

        return [
            'SIMPL-CLIENT-ID' => $clientId,
            'SIMPL-CLIENT-NONCE' => $nonce,
            'SIMPL-CLIENT-SIGNATURE' => $signature,
            'SHOP-DOMAIN' => $domain,
            'cache-control' => 'no-cache',
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Common function to call Simpl API
     * @param string $requestUrl
     * @param array $params
     * @param string $method
     * @return array
     */
    public function callSimplApi(string $endpointUrl, array $params = [], $method = 'POST')
    {
        $responseData = [];
        try{
            $hostUrl = $this->config->getApiUrl();
            $headers = $this->getHeaders();
            $body = $this->json->serialize($params);

            $this->logger->info( 'API Call initiated for '.$endpointUrl);

            if(!empty($hostUrl) && !empty($endpointUrl) && !empty($headers)){
                $requestUrl = $hostUrl.$endpointUrl;
                $this->logger->info( 'API Call initiated for '.$hostUrl.' '.$requestUrl);

                $this->logger->info(print_r($params,true));
                $startTime = time();
                $response = $this->client->request(
                    $method,
                    $requestUrl,
                    [
                        'headers' => $headers,
                        'body' => $body
                    ]
                );

                $responseData = [
                    'code' => $response->getStatusCode(),
                    'data' => $this->json->unserialize($response->getBody()->getContents())
                ];

                $this->logger->info(print_r($responseData,true));
                $endTime = time() - $startTime;
                $milliSeconds = $endTime * 1000;

                $this->logger->info('time taken by api to execute'.':'.' '.$milliSeconds);
            } else {
                $this->logger->info( 'Please configure API settings');
            }
            return $responseData;

        }catch (GuzzleException $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\InvalidArgumentException $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\Exception $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        }
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getDomain() {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }


    /**
     * @param string $nonce
     * @param string|null $clientId
     * @return false|string
     */
    private function generateSignature(string $nonce,string $clientId = null) {

        if (!$clientId)
            $clientId = $this->getClientId();

        $clientSecret = $this->getSecret();
        $data = $nonce . "-" . $clientId;

        return hash_hmac('sha1', $data, $clientSecret);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateUuid() {
        $b = random_bytes(16);
        $b[6] = chr(ord($b[6]) & 0x0f | 0x40);
        $b[8] = chr(ord($b[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
}
