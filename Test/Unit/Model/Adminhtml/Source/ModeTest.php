<?php
namespace Simpl\Checkout\Test\Unit\Model\Adminhtml\Source;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Adminhtml\Source\Mode
 */
class ModeTest extends TestCase
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
     * @var \Simpl\Checkout\Model\Adminhtml\Source\Mode
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);

        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Adminhtml\Source\Mode::class,
            [

            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestToOptionArray()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestToOptionArray
     */
    public function testToOptionArray(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
