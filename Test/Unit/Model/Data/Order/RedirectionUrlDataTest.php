<?php
namespace Simpl\Checkout\Test\Unit\Model\Data\Order;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Data\Order\RedirectionUrlData
 */
class RedirectionUrlDataTest extends TestCase
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
     * @var \Simpl\Checkout\Model\Data\Order\RedirectionUrlData
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);

        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Data\Order\RedirectionUrlData::class,
            [

            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetRedirectionUrl()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetRedirectionUrl
     */
    public function testGetRedirectionUrl(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetRedirectionUrl()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetRedirectionUrl
     */
    public function testSetRedirectionUrl(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
