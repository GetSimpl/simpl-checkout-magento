<?php
namespace Simpl\Checkout\Test\Unit\Controller\Adminhtml\Index;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Controller\Adminhtml\Index\ValidateSecret
 */
class ValidateSecretTest extends TestCase
{
    /**
     * Mock resultPageFactoryInstance
     *
     * @var \Magento\Framework\View\Result\Page|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactoryInstance;

    /**
     * Mock resultPageFactory
     *
     * @var \Magento\Framework\View\Result\PageFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultPageFactory;

    /**
     * Mock json
     *
     * @var \Magento\Framework\Serialize\Serializer\Json|PHPUnit\Framework\MockObject\MockObject
     */
    private $json;

    /**
     * Mock http
     *
     * @var \Magento\Framework\App\Response\Http|PHPUnit\Framework\MockObject\MockObject
     */
    private $http;

    /**
     * Mock request
     *
     * @var \Magento\Framework\App\RequestInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

    /**
     * Mock simplApi
     *
     * @var \Simpl\Checkout\Helper\SimplApi|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplApi;

    /**
     * Mock logger
     *
     * @var \Simpl\Checkout\Logger\Logger|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Controller\Adminhtml\Index\ValidateSecret
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->resultPageFactoryInstance = $this->createMock(\Magento\Framework\View\Result\Page::class);
        $this->resultPageFactory = $this->createMock(\Magento\Framework\View\Result\PageFactory::class);
        $this->resultPageFactory->method('create')->willReturn($this->resultPageFactoryInstance);
        $this->json = $this->createMock(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->http = $this->createMock(\Magento\Framework\App\Response\Http::class);
        $this->request = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->simplApi = $this->createMock(\Simpl\Checkout\Helper\SimplApi::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Controller\Adminhtml\Index\ValidateSecret::class,
            [
                'resultPageFactory' => $this->resultPageFactory,
                'json' => $this->json,
                'http' => $this->http,
                'request' => $this->request,
                'simplApi' => $this->simplApi,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
