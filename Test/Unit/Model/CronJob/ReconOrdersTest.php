<?php
namespace Simpl\Checkout\Test\Unit\Model\CronJob;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\CronJob\ReconOrders
 */
class ReconOrdersTest extends TestCase
{
    /**
     * Mock config
     *
     * @var \Simpl\Checkout\Helper\Config|PHPUnit\Framework\MockObject\MockObject
     */
    private $config;

    /**
     * Mock collectionFactoryInstance
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection|PHPUnit\Framework\MockObject\MockObject
     */
    private $collectionFactoryInstance;

    /**
     * Mock collectionFactory
     *
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $collectionFactory;

    /**
     * Mock orderManagement
     *
     * @var \Magento\Sales\Api\OrderManagementInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderManagement;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\CronJob\ReconOrders
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->config = $this->createMock(\Simpl\Checkout\Helper\Config::class);
        $this->collectionFactoryInstance = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\Collection::class);
        $this->collectionFactory = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\CollectionFactory::class);
        $this->collectionFactory->method('create')->willReturn($this->collectionFactoryInstance);
        $this->orderManagement = $this->createMock(\Magento\Sales\Api\OrderManagementInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\CronJob\ReconOrders::class,
            [
                'config' => $this->config,
                'collectionFactory' => $this->collectionFactory,
                'orderManagement' => $this->orderManagement,
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
