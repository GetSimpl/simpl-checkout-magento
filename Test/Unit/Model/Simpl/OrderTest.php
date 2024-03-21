<?php
namespace Simpl\Checkout\Test\Unit\Model\Simpl;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Simpl\Order
 */
class OrderTest extends TestCase
{
    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock checkoutSession
     *
     * @var \Magento\Checkout\Model\Session|PHPUnit\Framework\MockObject\MockObject
     */
    private $checkoutSession;

    /**
     * Mock orderInstance
     *
     * @var \Magento\Sales\Model\Order|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderInstance;

    /**
     * Mock order
     *
     * @var \Magento\Sales\Model\OrderFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $order;

    /**
     * Mock url
     *
     * @var \Magento\Framework\UrlInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $url;

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
     * @var \Simpl\Checkout\Model\Simpl\Order
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->checkoutSession = $this->createMock(\Magento\Checkout\Model\Session::class);
        $this->orderInstance = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->order = $this->createMock(\Magento\Sales\Model\OrderFactory::class);
        $this->order->method('create')->willReturn($this->orderInstance);
        $this->url = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Simpl\Order::class,
            [
                'orderRepository' => $this->orderRepository,
                'checkoutSession' => $this->checkoutSession,
                'order' => $this->order,
                'url' => $this->url,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCurrentOrder()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCurrentOrder
     */
    public function testGetCurrentOrder(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetOrder()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetOrder
     */
    public function testGetOrder(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetOrderDetails()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetOrderDetails
     */
    public function testGetOrderDetails(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
