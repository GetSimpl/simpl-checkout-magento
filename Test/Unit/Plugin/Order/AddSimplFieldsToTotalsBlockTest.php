<?php
namespace Simpl\Checkout\Test\Unit\Plugin\Order;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Plugin\Order\AddSimplFieldsToTotalsBlock
 */
class AddSimplFieldsToTotalsBlockTest extends TestCase
{
    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Plugin\Order\AddSimplFieldsToTotalsBlock
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);

        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Plugin\Order\AddSimplFieldsToTotalsBlock::class,
            [

            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestAfterGetOrder()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestAfterGetOrder
     */
    public function testAfterGetOrder(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
