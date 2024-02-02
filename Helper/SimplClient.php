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

    /**
     * @var Signature
     */
    protected $signature;

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
        AuthHelper $signature,
        Context $context
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $this->client = $client;
        $this->json = $json;
        $this->signature = $signature;
        parent::__construct($context);
    }

    /**
     * Function to prepare header
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Exception
     */
    private function getHeaders() {
        $clientId = $this->signature->getClientId();
        $nonce = $this->signature->generateUuid();
        $signature = $this->signature->generateSignature($nonce);
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
}
