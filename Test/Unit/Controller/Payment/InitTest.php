<?php
namespace Simpl\Checkout\Test\Unit\Controller\Payment;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Controller\Payment\Init
 */
class InitTest extends TestCase
{
    /**
     * Mock jsonFactoryInstance
     *
     * @var \Magento\Framework\Controller\Result\Json|PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonFactoryInstance;

    /**
     * Mock jsonFactory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonFactory;

    /**
     * Mock order
     *
     * @var \Simpl\Checkout\Model\Simpl\Order|PHPUnit\Framework\MockObject\MockObject
     */
    private $order;

    /**
     * Mock simplApi
     *
     * @var \Simpl\Checkout\Helper\SimplApi|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplApi;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Controller\Payment\Init
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->jsonFactoryInstance = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        $this->jsonFactory = $this->createMock(\Magento\Framework\Controller\Result\JsonFactory::class);
        $this->jsonFactory->method('create')->willReturn($this->jsonFactoryInstance);
        $this->order = $this->createMock(\Simpl\Checkout\Model\Simpl\Order::class);
        $this->simplApi = $this->createMock(\Simpl\Checkout\Helper\SimplApi::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Controller\Payment\Init::class,
            [
                'jsonFactory' => $this->jsonFactory,
                'order' => $this->order,
                'simplApi' => $this->simplApi,
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
