<?php
namespace Simpl\Checkout\Test\Unit\Model\Data;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Data\GetCreditmemoResponse
 */
class GetCreditmemoResponseTest extends TestCase
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
     * Mock creditMemoData
     *
     * @var \Simpl\Checkout\Api\Data\CreditMemoDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $creditMemoData;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\Data\GetCreditmemoResponse
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
        $this->creditMemoData = $this->createMock(\Simpl\Checkout\Api\Data\CreditMemoDataInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Data\GetCreditmemoResponse::class,
            [
                'apiData' => $this->apiData,
                'errorData' => $this->errorData,
                'messageData' => $this->messageData,
                'creditMemoData' => $this->creditMemoData,
            ]
        );
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

    /**
     * @return array
     */
    public function dataProviderForTestSetCreditMemo()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestSetCreditMemo
     */
    public function testSetCreditMemo(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestCreditMemoNotFoundError()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestCreditMemoNotFoundError
     */
    public function testCreditMemoNotFoundError(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
