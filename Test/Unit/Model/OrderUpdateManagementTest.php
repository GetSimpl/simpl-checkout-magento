<?php
namespace Simpl\Checkout\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\OrderUpdateManagement
 */
class OrderUpdateManagementTest extends TestCase
{
    /**
     * Mock orderFactoryInstance
     *
     * @var \Magento\Sales\Model\Order|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFactoryInstance;

    /**
     * Mock orderFactory
     *
     * @var \Magento\Sales\Model\OrderFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFactory;

    /**
     * Mock transactionBuilder
     *
     * @var \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $transactionBuilder;

    /**
     * Mock simplApi
     *
     * @var \Simpl\Checkout\Helper\SimplApi|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplApi;

    /**
     * Mock orderUpdateResponse
     *
     * @var \Simpl\Checkout\Model\Data\Order\OrderUpdateResponse|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderUpdateResponse;

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
     * @var \Simpl\Checkout\Model\OrderUpdateManagement
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderFactoryInstance = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->orderFactory = $this->createMock(\Magento\Sales\Model\OrderFactory::class);
        $this->orderFactory->method('create')->willReturn($this->orderFactoryInstance);
        $this->transactionBuilder = $this->createMock(\Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface::class);
        $this->simplApi = $this->createMock(\Simpl\Checkout\Helper\SimplApi::class);
        $this->orderUpdateResponse = $this->createMock(\Simpl\Checkout\Model\Data\Order\OrderUpdateResponse::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\OrderUpdateManagement::class,
            [
                'orderFactory' => $this->orderFactory,
                'transactionBuilder' => $this->transactionBuilder,
                'simplApi' => $this->simplApi,
                'orderUpdateResponse' => $this->orderUpdateResponse,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestUpdate()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestUpdate
     */
    public function testUpdate(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
