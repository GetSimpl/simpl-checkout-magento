<?php
namespace Simpl\Checkout\Test\Unit\Model;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\OrderConfirmManagement
 */
class OrderConfirmManagementTest extends TestCase
{
    /**
     * Mock orderFactoryInstance
     *
     * @var \Magento\Sales\Model\Order|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFactoryInstance;

    /**
     * Mock orderFactory
     *
     * @var \Magento\Sales\Model\OrderFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderFactory;

    /**
     * Mock transactionBuilder
     *
     * @var \Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $transactionBuilder;

    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock invoiceService
     *
     * @var \Magento\Sales\Model\Service\InvoiceService|PHPUnit\Framework\MockObject\MockObject
     */
    private $invoiceService;

    /**
     * Mock invoiceSender
     *
     * @var \Magento\Sales\Model\Order\Email\Sender\InvoiceSender|PHPUnit\Framework\MockObject\MockObject
     */
    private $invoiceSender;

    /**
     * Mock invoiceRepository
     *
     * @var \Magento\Sales\Api\InvoiceRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $invoiceRepository;

    /**
     * Mock creditmemoRepository
     *
     * @var \Magento\Sales\Api\CreditmemoRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $creditmemoRepository;

    /**
     * Mock simplApi
     *
     * @var \Simpl\Checkout\Helper\SimplApi|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplApi;

    /**
     * Mock orderData
     *
     * @var \Simpl\Checkout\Api\Data\OrderDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderData;

    /**
     * Mock creditMemoData
     *
     * @var \Simpl\Checkout\Api\Data\CreditMemoDataInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $creditMemoData;

    /**
     * Mock orderConfirmResponse
     *
     * @var \Simpl\Checkout\Model\Data\Order\OrderConfirmResponse|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderConfirmResponse;

    /**
     * Mock config
     *
     * @var \Simpl\Checkout\Helper\Config|PHPUnit\Framework\MockObject\MockObject
     */
    private $config;

    /**
     * Mock logger
     *
     * @var \Simpl\Checkout\Logger\Logger|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * Mock simplFactoryInstance
     *
     * @var \Simpl\Checkout\Model\SimplOrder|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplFactoryInstance;

    /**
     * Mock simplFactory
     *
     * @var \Simpl\Checkout\Model\SimplOrderFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplFactory;

    /**
     * Mock simplResource
     *
     * @var \Simpl\Checkout\Model\ResourceModel\SimplOrder|PHPUnit\Framework\MockObject\MockObject
     */
    private $simplResource;

    /**
     * Mock orderEmailSender
     *
     * @var \Magento\Sales\Model\Order\Email\Sender\OrderSender|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderEmailSender;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\OrderConfirmManagement
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderFactoryInstance = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->orderFactory = $this->createMock(\Magento\Sales\Model\OrderFactory::class);
        $this->orderFactory->method('create')->willReturn($this->orderFactoryInstance);
        $this->transactionBuilder = $this->createMock(\Magento\Sales\Model\Order\Payment\Transaction\BuilderInterface::class);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->invoiceService = $this->createMock(\Magento\Sales\Model\Service\InvoiceService::class);
        $this->invoiceSender = $this->createMock(\Magento\Sales\Model\Order\Email\Sender\InvoiceSender::class);
        $this->invoiceRepository = $this->createMock(\Magento\Sales\Api\InvoiceRepositoryInterface::class);
        $this->creditmemoRepository = $this->createMock(\Magento\Sales\Api\CreditmemoRepositoryInterface::class);
        $this->simplApi = $this->createMock(\Simpl\Checkout\Helper\SimplApi::class);
        $this->orderData = $this->createMock(\Simpl\Checkout\Api\Data\OrderDataInterface::class);
        $this->creditMemoData = $this->createMock(\Simpl\Checkout\Api\Data\CreditMemoDataInterface::class);
        $this->orderConfirmResponse = $this->createMock(\Simpl\Checkout\Model\Data\Order\OrderConfirmResponse::class);
        $this->config = $this->createMock(\Simpl\Checkout\Helper\Config::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->simplFactoryInstance = $this->createMock(\Simpl\Checkout\Model\SimplOrder::class);
        $this->simplFactory = $this->createMock(\Simpl\Checkout\Model\SimplOrderFactory::class);
        $this->simplFactory->method('create')->willReturn($this->simplFactoryInstance);
        $this->simplResource = $this->createMock(\Simpl\Checkout\Model\ResourceModel\SimplOrder::class);
        $this->orderEmailSender = $this->createMock(\Magento\Sales\Model\Order\Email\Sender\OrderSender::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\OrderConfirmManagement::class,
            [
                'orderFactory' => $this->orderFactory,
                'transactionBuilder' => $this->transactionBuilder,
                'orderRepository' => $this->orderRepository,
                'invoiceService' => $this->invoiceService,
                'invoiceSender' => $this->invoiceSender,
                'invoiceRepository' => $this->invoiceRepository,
                'creditmemoRepository' => $this->creditmemoRepository,
                'simplApi' => $this->simplApi,
                'orderData' => $this->orderData,
                'creditMemoData' => $this->creditMemoData,
                'orderConfirmResponse' => $this->orderConfirmResponse,
                'config' => $this->config,
                'logger' => $this->logger,
                'simplFactory' => $this->simplFactory,
                'simplResource' => $this->simplResource,
                'orderEmailSender' => $this->orderEmailSender,
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
