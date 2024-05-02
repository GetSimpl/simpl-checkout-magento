<?php
namespace Simpl\Checkout\Test\Unit\Model\Data\Order;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Data\Order\OrderConfirmResponse
 */
class OrderConfirmResponseTest extends TestCase
{
    /**
     * Mock apiData
     *
     * @var \Simpl\Checkout\Api\Data\ApiDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $apiData;

    /**
     * Mock errorData
     *
     * @var \Simpl\Checkout\Api\Data\ErrorDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $errorData;

    /**
     * Mock messageData
     *
     * @var \Simpl\Checkout\Api\Data\MessageDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $messageData;

    /**
     * Mock redirectionUrlData
     *
     * @var \Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $redirectionUrlData;

    /**
     * Mock orderConfirmSuccessData
     *
     * @var \Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderConfirmSuccessData;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\Data\Order\OrderConfirmResponse
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->apiData = $this->createMock(\Simpl\Checkout\Api\Data\ApiDataInterface::class);
        $this->errorData = $this->createMock(\Simpl\Checkout\Api\Data\ErrorDataInterface::class);
        $this->messageData = $this->createMock(\Simpl\Checkout\Api\Data\MessageDataInterface::class);
        $this->redirectionUrlData = $this->createMock(\Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface::class);
        $this->orderConfirmSuccessData = $this->createMock(\Simpl\Checkout\Api\Data\Order\OrderConfirmSuccessDataInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Data\Order\OrderConfirmResponse::class,
            [
                'apiData' => $this->apiData,
                'errorData' => $this->errorData,
                'messageData' => $this->messageData,
                'redirectionUrlData' => $this->redirectionUrlData,
                'orderConfirmSuccessData' => $this->orderConfirmSuccessData,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetRedirectionURL()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetRedirectionURL
     */
    public function testSetRedirectionURL(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetError()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetError
     */
    public function testSetError(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestSetMessage()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetMessage
     */
    public function testSetMessage(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
