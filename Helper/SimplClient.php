<?php

namespace Simpl\Checkout\Helper;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Logger\Logger;

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
     * @var AuthHelper
     */
    protected $authHelper;

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
        AuthHelper $authHelper,
        Context $context
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->client = $client;
        $this->json = $json;
        $this->authHelper = $authHelper;
        parent::__construct($context);
    }

    /**
     * Function to prepare header
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

    /**
     * @param string $endpointUrl
     * @param array $body
     * @return Response
     */
    public function postRequest(string $endpointUrl, array $body = [])
    {

        $hostUrl = $this->config->getApiUrl();
        $requestUrl = $hostUrl.$endpointUrl;
        $responseArray = ["success" => false];
        $body = $this->json->serialize($body);

        try {
            $header = $this->getHeaders();

            // LOG
            $this->logger->info('API Call initiated for : '.$endpointUrl);
            $this->logger->info($body);
            $startTime = time();

            $response = $this->client->request(
                'POST',
                $requestUrl,
                [
                    'headers' => $header,
                    'body' => $body
                ]
            );
            $contents = $response->getBody()->getContents();
            $responseArray = $this->json->unserialize($contents);
            $this->logger->info('API Response : ' . $contents);
            $this->logger->info('API Status code : ' . $response->getStatusCode());

            // LOG
            $endTime = time() - $startTime;
            $milliSeconds = $endTime * 1000;
            $this->logger->info('time taken by api to execute'.':'.' '.$milliSeconds);
        } catch (GuzzleException $exception) {

            if (!isset($responseArray["error"]["message"])) {
                $responseArray["error"]["message"] = $exception->getMessage();
            }
            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\InvalidArgumentException | \Exception $exception) {

            if (!isset($responseArray["error"]["message"])) {
                $responseArray["error"]["message"] = $exception->getMessage();
            }
            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        }

        return new Response($responseArray);
    }

    /**
     * @param string $endpointUrl
     * @param array $params
     * @return Response
     */
    public function getRequest(string $endpointUrl, array $params = [])
    {

        $hostUrl = $this->config->getApiUrl();
        $requestUrl = $hostUrl.$endpointUrl;
        $responseArray = ["success" => false];

        try {
            // LOG
            $this->logger->info('API Call initiated for '.$endpointUrl);
            $this->logger->info(print_r($params, true));
            $startTime = time();

            $response = $this->client->request(
                'GET',
                $requestUrl,
                [
                    'headers' => $this->getHeaders(),
                    'query' => $params
                ]
            );
            $responseArray = $this->json->unserialize($response->getBody()->getContents());
            $this->logger->info(print_r($responseArray, true));

            // LOG
            $endTime = time() - $startTime;
            $milliSeconds = $endTime * 1000;
            $this->logger->info('time taken by api to execute'.':'.' '.$milliSeconds);
        } catch (GuzzleException $exception) {

            if (!isset($responseArray["error"]["message"])) {
                $responseArray["error"]["message"] = $exception->getMessage();
            }
            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        } catch (\InvalidArgumentException | \Exception $exception) {

            if (!isset($responseArray["error"]["message"])) {
                $responseArray["error"]["message"] = $exception->getMessage();
            }
            $this->logger->error('Exception ' . get_class($exception) . ' while API call: ' . $exception->getMessage());
        }

        return new Response($responseArray);
    }
}
