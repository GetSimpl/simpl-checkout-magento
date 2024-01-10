<?php

namespace Simpl\Checkout\Helper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Logger\Logger;
use Magento\Store\Model\StoreManagerInterface;

class Simpl extends AbstractHelper
{
    /**
     * @var GuzzleHttpClient
     */
    private $client;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

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
     * @param $clientId
     * @param $nonce
     * @param $signature
     * @return bool
     * @throws \Exception
     */
    public function validateSignature($clientId, $nonce, $signature) {
        $localSignature = $this->generateSignature($nonce, $clientId);
        if ($signature == $localSignature)
            return true;
        return false;
    }

    /**
     * Common function to call Simpl API
     * @param $requestUrl
     * @param $params
     * @param string $method
     * @return array
     */
    public function callSimplApi($endpointUrl,$params,$method = 'POST')
    {
        $responseData = [];
        try{
            $clientId = $this->config->getClientId();
            $secret = $this->config->getSecret();
            $nonce = $this->generateUuid();
            $signature = $this->generateSignature($nonce);
            $hostUrl = $this->config->getApiUrl();
            $domain = $this->getDomain();
            $body = $this->json->serialize($params);

            $this->logger->info( 'API Call initiated for '.$endpointUrl);

            if(!empty($clientId) &&  !empty($secret) && !empty($hostUrl) && !empty($endpointUrl) && !empty($signature)){
                $requestUrl = $hostUrl.$endpointUrl;
                $this->logger->info( 'API Call initiated for '.$hostUrl.' '.$requestUrl);
                $headers = [
                    'SIMPL-CLIENT-ID' => $clientId,
                    'SIMPL-CLIENT-NONCE' => $nonce,
                    'SIMPL-CLIENT-SIGNATURE' => $signature,
                    'SHOP-DOMAIN' => $domain,
                    'cache-control' => 'no-cache',
                    'Content-Type' => 'application/json'
                ];

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
    public function getDomain() {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
    }


    /**
     * @return false|string
     * @throws \Exception
     */
    public function generateSignature($nonce, $clientId = null) {

        if (!$clientId)
            $clientId = $this->config->getClientId();

        $clientSecret = $this->config->getSecret();
        $data = $nonce . "-" . $clientId;

        return hash_hmac('sha1', $data, $clientSecret);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateUuid() {
        $b = random_bytes(16);
        $b[6] = chr(ord($b[6]) & 0x0f | 0x40);
        $b[8] = chr(ord($b[8]) & 0x3f | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
    }
}
