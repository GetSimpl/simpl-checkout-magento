<?php
namespace Simpl\Checkout\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\GetOrderDetails
 */
class GetOrderDetailsTest extends TestCase
{
    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock getOrderResponse
     *
     * @var \Simpl\Checkout\Model\Data\Order\GetOrderResponse|PHPUnit\Framework\MockObject\MockObject
     */
    private $getOrderResponse;

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
     * @var \Simpl\Checkout\Model\GetOrderDetails
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->getOrderResponse = $this->createMock(\Simpl\Checkout\Model\Data\Order\GetOrderResponse::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\GetOrderDetails::class,
            [
                'orderRepository' => $this->orderRepository,
                'getOrderResponse' => $this->getOrderResponse,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGet()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGet
     */
    public function testGet(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
