<?php
namespace Simpl\Checkout\Test\Unit\Events;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Events\PaymentInfoSubmitted
 */
class PaymentInfoSubmittedTest extends TestCase
{
    /**
     * Mock simplApi
     *
     * @var \Simpl\Checkout\Helper\SimplApi|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplApi;

    /**
     * Mock cart
     *
     * @var \Simpl\Checkout\Model\Simpl\Cart|PHPUnit\Framework\MockObject\MockObject
     */
    private $cart;

    /**
     * Mock user
     *
     * @var \Simpl\Checkout\Model\Simpl\User|PHPUnit\Framework\MockObject\MockObject
     */
    private $user;

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
     * @var \Simpl\Checkout\Events\PaymentInfoSubmitted
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->simplApi = $this->createMock(\Simpl\Checkout\Helper\SimplApi::class);
        $this->cart = $this->createMock(\Simpl\Checkout\Model\Simpl\Cart::class);
        $this->user = $this->createMock(\Simpl\Checkout\Model\Simpl\User::class);
        $this->request = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $this->json = $this->createMock(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Events\PaymentInfoSubmitted::class,
            [
                'simplApi' => $this->simplApi,
                'cart' => $this->cart,
                'user' => $this->user,
                'request' => $this->request,
                'json' => $this->json,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestHandle()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestHandle
     */
    public function testHandle(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
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
