<?php
namespace Simpl\Checkout\Test\Unit\Model\Ui;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Ui\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    /**
     * Mock config
     *
     * @var \Simpl\Checkout\Helper\Config|PHPUnit\Framework\MockObject\MockObject
     */
    private $config;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\Ui\ConfigProvider
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->config = $this->createMock(\Simpl\Checkout\Helper\Config::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Ui\ConfigProvider::class,
            [
                'config' => $this->config,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetConfig()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetConfig
     */
    public function testGetConfig(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
