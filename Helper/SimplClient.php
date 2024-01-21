<?php

namespace Simpl\Checkout\Helper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Logger\Logger;

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
        Context $context
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->client = $client;
        $this->json = $json;
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
     * @param string $endpointUrl
     * @param array $body
     * @return Response
     */
    public function postRequest(string $endpointUrl, array $body = []) {

        $hostUrl = $this->config->getApiUrl();
        $requestUrl = $hostUrl.$endpointUrl;
        $responseArray = ["success" => false];

        try{

            // LOG
            $this->logger->info( 'API Call initiated for '.$endpointUrl);
            $this->logger->info(print_r($body,true));
            $startTime = time();

            $response = $this->client->request(
                'POST',
                $requestUrl,
                [
                    'headers' => $this->getHeaders(),
                    'body' => $this->json->serialize($body)
                ]
            );
            $responseArray = $this->json->unserialize($response->getBody()->getContents());

            // LOG
            $endTime = time() - $startTime;
            $milliSeconds = $endTime * 1000;
            $this->logger->info('time taken by api to execute'.':'.' '.$milliSeconds);
        }catch (GuzzleException $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\InvalidArgumentException | \Exception $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        }

        return new Response($responseArray);
    }

    /**
     * @param string $endpointUrl
     * @param array $params
     * @return Response
     */
    public function getRequest(string $endpointUrl, array $params = []) {

        $hostUrl = $this->config->getApiUrl();
        $requestUrl = $hostUrl.$endpointUrl;
        $responseArray = ["success" => false];

        try{

            // LOG
            $this->logger->info( 'API Call initiated for '.$endpointUrl);
            $this->logger->info(print_r($params,true));
            $startTime = time();

            $response = $this->client->request(
                'GET',
                $requestUrl,
                [
                    'headers' => $this->getHeaders(),
                    'query' => $this->json->serialize($params)
                ]
            );
            $responseArray = $this->json->unserialize($response->getBody()->getContents());

            // LOG
            $endTime = time() - $startTime;
            $milliSeconds = $endTime * 1000;
            $this->logger->info('time taken by api to execute'.':'.' '.$milliSeconds);

        }catch (GuzzleException $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\InvalidArgumentException | \Exception $exception) {

            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        }

        return new Response($responseArray);
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
