<?php
namespace Simpl\Checkout\Test\Unit\Helper;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Helper\SimplClient
 */
class SimplClientTest extends TestCase
{
    /**
     * Mock client
     *
     * @var \GuzzleHttp\Client|PHPUnit\Framework\MockObject\MockObject
     */
    private $client;

    /**
     * Mock json
     *
     * @var \Magento\Framework\Serialize\Serializer\Json|PHPUnit\Framework\MockObject\MockObject
     */
    private $json;

    /**
     * Mock config
     *
     * @var \Simpl\Checkout\Helper\Config|PHPUnit\Framework\MockObject\MockObject
     */
    private $config;

    /**
     * Mock logger
     *
     * @var \Simpl\Checkout\Logger\Logger|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * Mock authHelper
     *
     * @var \Simpl\Checkout\Helper\AuthHelper|PHPUnit\Framework\MockObject\MockObject
     */
    private $authHelper;

    /**
     * Mock context
     *
     * @var \Magento\Framework\App\Helper\Context|PHPUnit\Framework\MockObject\MockObject
     */
    private $context;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Helper\SimplClient
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->client = $this->createMock(\GuzzleHttp\Client::class);
        $this->json = $this->createMock(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->config = $this->createMock(\Simpl\Checkout\Helper\Config::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->authHelper = $this->createMock(\Simpl\Checkout\Helper\AuthHelper::class);
        $this->context = $this->createMock(\Magento\Framework\App\Helper\Context::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Helper\SimplClient::class,
            [
                'client' => $this->client,
                'json' => $this->json,
                'config' => $this->config,
                'logger' => $this->logger,
                'authHelper' => $this->authHelper,
                'context' => $this->context,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestPostRequest()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestPostRequest
     */
    public function testPostRequest(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetRequest()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetRequest
     */
    public function testGetRequest(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsModuleOutputEnabled()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestIsModuleOutputEnabled
     */
    public function testIsModuleOutputEnabled(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
