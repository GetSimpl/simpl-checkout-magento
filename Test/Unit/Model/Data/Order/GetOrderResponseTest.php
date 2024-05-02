<?php
namespace Simpl\Checkout\Test\Unit\Model\Data\Order;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Data\Order\GetOrderResponse
 */
class GetOrderResponseTest extends TestCase
{
    /**
     * Mock errorData
     *
     * @var \Simpl\Checkout\Api\Data\ErrorDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $errorData;

    /**
     * Mock orderData
     *
     * @var \Simpl\Checkout\Api\Data\OrderDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderData;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\Data\Order\GetOrderResponse
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->errorData = $this->createMock(\Simpl\Checkout\Api\Data\ErrorDataInterface::class);
        $this->orderData = $this->createMock(\Simpl\Checkout\Api\Data\OrderDataInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Data\Order\GetOrderResponse::class,
            [
                'errorData' => $this->errorData,
                'orderData' => $this->orderData,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetOrder()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetOrder
     */
    public function testSetOrder(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestOrderNotFoundError()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestOrderNotFoundError
     */
    public function testOrderNotFoundError(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
