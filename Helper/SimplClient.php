<?php

namespace Simpl\Checkout\Helper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Logger\Logger;
use Simpl\Checkout\Api\Data\ApiResponseDataInterface;
use Simpl\Checkout\Api\Data\ErrorDataInterface;

class SimplClient extends AbstractHelper {

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
     * @var ApiResponseDataInterface
     */
    protected $apiResponseData;

    /**
     * @var ErrorDataInterface
     */
    protected $errorData;

    private $clientId;

    private $secret;

    /**
     * @param GuzzleHttpClient $client
     * @param Json $json
     * @param Config $config
     * @param Logger $logger
     * @param ApiResponseDataInterface $apiResponseData
     * @param Context $context
     */
    public function __construct(
        GuzzleHttpClient $client,
        Json $json,
        Config $config,
        Logger $logger,
        ApiResponseDataInterface $apiResponseData,
        ErrorDataInterface $errorData,
        Context $context
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->client = $client;
        $this->json = $json;
        $this->apiResponseData = $apiResponseData;
        $this->errorData = $errorData;
        $this->clientId = $this->config->getClientId();
        $this->secret = $this->config->getSecret();
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
        $clientId = $this->clientId;
        $nonce = $this->generateUuid();
        $signature = $this->generateSignature($nonce);
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

    /**
     * @param string $method
     * @param string $requestUrl
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function initPostRequest(string $method, string $requestUrl, array $params = []) {
        return $this->client->request(
            $method,
            $requestUrl,
            [
                'headers' => $this->getHeaders(),
                'body' => $this->json->serialize($params)
            ]
        );
    }

    /**
     * @param string $method
     * @param string $requestUrl
     * @param array $params
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function initGetRequest(string $method, string $requestUrl, array $params = []) {
        return $this->client->request(
            $method,
            $requestUrl,
            [
                'headers' => $this->getHeaders(),
                'query' => $this->json->serialize($params)
            ]
        );
    }

    /**
     * @param $response
     * @return bool
     */
    private function isSuccess($response) {
        if (isset($response["success"]) and $response["success"] == true) {
            return true;
        }
        return false;
    }

    /**
     *
     * Common function to call Simpl API
     * @param string $endpointUrl
     * @param array $params
     * @param string $method
     * @return ApiResponseDataInterface
     */
    public function callSimplApi(string $endpointUrl, array $params = [], $method = 'POST') {
        $responseData = $this->apiResponseData;
        try{
            $hostUrl = $this->config->getApiUrl();

            $this->logger->info( 'API Call initiated for '.$endpointUrl);

            if(!empty($hostUrl) && !empty($endpointUrl) && !empty($headers)){
                $requestUrl = $hostUrl.$endpointUrl;
                $this->logger->info( 'API Call initiated for '.$hostUrl.' '.$requestUrl);

                $this->logger->info(print_r($params,true));
                $startTime = time();

                if ($method == 'POST') {
                    $response = $this->initPostRequest($method, $requestUrl, $params);
                } else {
                    $response = $this->initGetRequest($method, $requestUrl, $params);
                }

                $responseArray = $this->json->unserialize($response->getBody()->getContents());

                if ($this->isSuccess($responseArray)) {
                    $responseData->setSuccess(true);
                    if (isset($responseArray["data"]))
                        $responseData->setData($responseArray["data"]);
                } else {
                    $error = $this->errorData;
                    $error->setCode($responseArray["error"]["code"]);
                    $error->setCode($responseArray["error"]["message"]);
                    $responseData->setError($error);
                }

                $endTime = time() - $startTime;
                $milliSeconds = $endTime * 1000;

                $this->logger->info('time taken by api to execute'.':'.' '.$milliSeconds);
            } else {
                $this->logger->info( 'Please configure API settings');
            }

        }catch (GuzzleException $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\InvalidArgumentException $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\Exception $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        }

        return $responseData;
    }


    /**
     * @param string $nonce
     * @param string|null $clientId
     * @return false|string
     */
    private function generateSignature(string $nonce,string $clientId = null) {

        if (!$clientId)
            $clientId = $this->clientId;

        $clientSecret = $this->secret;
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
