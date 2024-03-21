<?php
namespace Simpl\Checkout\Test\Unit\Events;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Events\AbstractEvents
 */
class AbstractEventsTest extends TestCase
{
    /**
     * Mock request
     *
     * @var \Magento\Framework\App\Request\Http|PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

    /**
     * Mock json
     *
     * @var \Magento\Framework\Serialize\Serializer\Json|PHPUnit\Framework\MockObject\MockObject
     */
    private $json;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Events\AbstractEvents
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->request = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $this->json = $this->createMock(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Events\AbstractEvents::class,
            [
                'request' => $this->request,
                'json' => $this->json,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetFingerprint()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetFingerprint
     */
    public function testGetFingerprint(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetParam()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetParam
     */
    public function testGetParam(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
