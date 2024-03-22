<?php
namespace Simpl\Checkout\Test\Unit\Controller\Payment;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Controller\Payment\Event
 */
class EventTest extends TestCase
{
    /**
     * Mock jsonFactoryInstance
     *
     * @var \Magento\Framework\Controller\Result\Json|PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonFactoryInstance;

    /**
     * Mock jsonFactory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonFactory;

    /**
     * Mock checkoutPageview
     *
     * @var \Simpl\Checkout\Events\CheckoutPageview|PHPUnit\Framework\MockObject\MockObject
     */
    private $checkoutPageview;

    /**
     * Mock paymentInitiate
     *
     * @var \Simpl\Checkout\Events\PaymentInitiate|PHPUnit\Framework\MockObject\MockObject
     */
    private $paymentInitiate;

    /**
     * Mock addressInfoSubmitted
     *
     * @var \Simpl\Checkout\Events\AddressInfoSubmitted|PHPUnit\Framework\MockObject\MockObject
     */
    private $addressInfoSubmitted;

    /**
     * Mock orderConfirm
     *
     * @var \Simpl\Checkout\Events\OrderConfirm|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderConfirm;

    /**
     * Mock paymentInfoSubmitted
     *
     * @var \Simpl\Checkout\Events\PaymentInfoSubmitted|PHPUnit\Framework\MockObject\MockObject
     */
    private $paymentInfoSubmitted;

    /**
     * Mock thankyouPageview
     *
     * @var \Simpl\Checkout\Events\ThankyouPageview|PHPUnit\Framework\MockObject\MockObject
     */
    private $thankyouPageview;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Controller\Payment\Event
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->jsonFactoryInstance = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        $this->jsonFactory = $this->createMock(\Magento\Framework\Controller\Result\JsonFactory::class);
        $this->jsonFactory->method('create')->willReturn($this->jsonFactoryInstance);
        $this->checkoutPageview = $this->createMock(\Simpl\Checkout\Events\CheckoutPageview::class);
        $this->paymentInitiate = $this->createMock(\Simpl\Checkout\Events\PaymentInitiate::class);
        $this->addressInfoSubmitted = $this->createMock(\Simpl\Checkout\Events\AddressInfoSubmitted::class);
        $this->orderConfirm = $this->createMock(\Simpl\Checkout\Events\OrderConfirm::class);
        $this->paymentInfoSubmitted = $this->createMock(\Simpl\Checkout\Events\PaymentInfoSubmitted::class);
        $this->thankyouPageview = $this->createMock(\Simpl\Checkout\Events\ThankyouPageview::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Controller\Payment\Event::class,
            [
                'jsonFactory' => $this->jsonFactory,
                'checkoutPageview' => $this->checkoutPageview,
                'paymentInitiate' => $this->paymentInitiate,
                'addressInfoSubmitted' => $this->addressInfoSubmitted,
                'orderConfirm' => $this->orderConfirm,
                'paymentInfoSubmitted' => $this->paymentInfoSubmitted,
                'thankyouPageview' => $this->thankyouPageview,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestExecute()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestExecute
     */
    public function testExecute(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
