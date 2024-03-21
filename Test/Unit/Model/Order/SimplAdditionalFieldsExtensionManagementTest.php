<?php
namespace Simpl\Checkout\Test\Unit\Model\Order;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement
 */
class SimplAdditionalFieldsExtensionManagementTest extends TestCase
{
    /**
     * Mock orderExtensionFactoryInstance
     *
     * @var \Magento\Sales\Api\Data\OrderExtension|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderExtensionFactoryInstance;

    /**
     * Mock orderExtensionFactory
     *
     * @var \Magento\Sales\Api\Data\OrderExtensionFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderExtensionFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderExtensionFactoryInstance = $this->createMock(\Magento\Sales\Api\Data\OrderExtension::class);
        $this->orderExtensionFactory = $this->createMock(\Magento\Sales\Api\Data\OrderExtensionFactory::class);
        $this->orderExtensionFactory->method('create')->willReturn($this->orderExtensionFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Order\SimplAdditionalFieldsExtensionManagement::class,
            [
                'orderExtensionFactory' => $this->orderExtensionFactory,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetExtensionFromData()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetExtensionFromData
     */
    public function testSetExtensionFromData(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetExtensionFromAddressData()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetExtensionFromAddressData
     */
    public function testSetExtensionFromAddressData(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetDataFromExtension()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetDataFromExtension
     */
    public function testSetDataFromExtension(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
