<?php
namespace Simpl\Checkout\Test\Unit\Plugin\Order;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Plugin\Order\SaveSimplFields
 */
class SaveSimplFieldsTest extends TestCase
{
    /**
     * Mock extensionManagement
     *
     * @var \Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement|PHPUnit\Framework\MockObject\MockObject
     */
    private $extensionManagement;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Plugin\Order\SaveSimplFields
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->extensionManagement = $this->createMock(\Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Plugin\Order\SaveSimplFields::class,
            [
                'extensionManagement' => $this->extensionManagement,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestBeforeSave()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestBeforeSave
     */
    public function testBeforeSave(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
