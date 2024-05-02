<?php
namespace Simpl\Checkout\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\RefundManagement
 */
class RefundManagementTest extends TestCase
{
    /**
     * Mock refundConfirmResponse
     *
     * @var \Simpl\Checkout\Model\Data\RefundConfirmResponse|PHPUnit\Framework\MockObject\MockObject
     */
    private $refundConfirmResponse;

    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock creditmemoRepository
     *
     * @var \Magento\Sales\Model\Order\CreditmemoRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $creditmemoRepository;

    /**
     * Mock creditmemoComment
     *
     * @var \Magento\Sales\Api\Data\CreditmemoCommentInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $creditmemoComment;

    /**
     * Mock simplApi
     *
     * @var \Simpl\Checkout\Helper\SimplApi|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplApi;

    /**
     * Mock logger
     *
     * @var \Simpl\Checkout\Logger\Logger|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * Mock transactionRepository
     *
     * @var \Magento\Sales\Api\TransactionRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $transactionRepository;

    /**
     * Mock transactionSearchResultInterfaceFactoryInstance
     *
     * @var \Magento\Sales\Api\Data\TransactionSearchResultInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $transactionSearchResultInterfaceFactoryInstance;

    /**
     * Mock transactionSearchResultInterfaceFactory
     *
     * @var \Magento\Sales\Api\Data\TransactionSearchResultInterfaceFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $transactionSearchResultInterfaceFactory;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\RefundManagement
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->refundConfirmResponse = $this->createMock(\Simpl\Checkout\Model\Data\RefundConfirmResponse::class);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->creditmemoRepository = $this->createMock(\Magento\Sales\Model\Order\CreditmemoRepository::class);
        $this->creditmemoComment = $this->createMock(\Magento\Sales\Api\Data\CreditmemoCommentInterface::class);
        $this->simplApi = $this->createMock(\Simpl\Checkout\Helper\SimplApi::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->transactionRepository = $this->createMock(\Magento\Sales\Api\TransactionRepositoryInterface::class);
        $this->transactionSearchResultInterfaceFactoryInstance = $this->createMock(\Magento\Sales\Api\Data\TransactionSearchResultInterface::class);
        $this->transactionSearchResultInterfaceFactory = $this->createMock(\Magento\Sales\Api\Data\TransactionSearchResultInterfaceFactory::class);
        $this->transactionSearchResultInterfaceFactory->method('create')->willReturn($this->transactionSearchResultInterfaceFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\RefundManagement::class,
            [
                'refundConfirmResponse' => $this->refundConfirmResponse,
                'orderRepository' => $this->orderRepository,
                'creditmemoRepository' => $this->creditmemoRepository,
                'creditmemoComment' => $this->creditmemoComment,
                'simplApi' => $this->simplApi,
                'logger' => $this->logger,
                'transactionRepository' => $this->transactionRepository,
                'transactionSearchResultInterfaceFactory' => $this->transactionSearchResultInterfaceFactory,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestConfirm()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestConfirm
     */
    public function testConfirm(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
