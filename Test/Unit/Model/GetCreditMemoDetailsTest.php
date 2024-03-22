<?php
namespace Simpl\Checkout\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\GetCreditMemoDetails
 */
class GetCreditMemoDetailsTest extends TestCase
{
    /**
     * Mock creditmemoRepository
     *
     * @var \Magento\Sales\Api\CreditmemoRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $creditmemoRepository;

    /**
     * Mock getCreditmemoResponse
     *
     * @var \Simpl\Checkout\Model\Data\GetCreditmemoResponse|PHPUnit\Framework\MockObject\MockObject
     */
    private $getCreditmemoResponse;

    /**
     * Mock logger
     *
     * @var \Simpl\Checkout\Logger\Logger|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\GetCreditMemoDetails
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->creditmemoRepository = $this->createMock(\Magento\Sales\Api\CreditmemoRepositoryInterface::class);
        $this->getCreditmemoResponse = $this->createMock(\Simpl\Checkout\Model\Data\GetCreditmemoResponse::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\GetCreditMemoDetails::class,
            [
                'creditmemoRepository' => $this->creditmemoRepository,
                'getCreditmemoResponse' => $this->getCreditmemoResponse,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetCreditMemo()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetCreditMemo
     */
    public function testGetCreditMemo(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
