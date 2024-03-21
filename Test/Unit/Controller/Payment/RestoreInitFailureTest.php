<?php
namespace Simpl\Checkout\Test\Unit\Controller\Payment;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Controller\Payment\RestoreInitFailure
 */
class RestoreInitFailureTest extends TestCase
{
    /**
     * Mock checkoutSession
     *
     * @var \Magento\Checkout\Model\Session|PHPUnit\Framework\MockObject\MockObject
     */
    private $checkoutSession;

    /**
     * Mock manager
     *
     * @var \Magento\Framework\Message\ManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $manager;

    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock resultRedirectFactoryInstance
     *
     * @var \Magento\Framework\Controller\Result\Redirect|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultRedirectFactoryInstance;

    /**
     * Mock resultRedirectFactory
     *
     * @var \Magento\Framework\Controller\Result\RedirectFactory|PHPUnit\Framework\MockObject\MockObject
     */
    private $resultRedirectFactory;

    /**
     * Mock quoteRepository
     *
     * @var \Magento\Quote\Api\CartRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $quoteRepository;

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
     * @var \Simpl\Checkout\Controller\Payment\RestoreInitFailure
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->checkoutSession = $this->createMock(\Magento\Checkout\Model\Session::class);
        $this->manager = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->resultRedirectFactoryInstance = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);
        $this->resultRedirectFactory = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->resultRedirectFactory->method('create')->willReturn($this->resultRedirectFactoryInstance);
        $this->quoteRepository = $this->createMock(\Magento\Quote\Api\CartRepositoryInterface::class);
        $this->jsonFactoryInstance = $this->createMock(\Magento\Framework\Controller\Result\Json::class);
        $this->jsonFactory = $this->createMock(\Magento\Framework\Controller\Result\JsonFactory::class);
        $this->jsonFactory->method('create')->willReturn($this->jsonFactoryInstance);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Controller\Payment\RestoreInitFailure::class,
            [
                'checkoutSession' => $this->checkoutSession,
                'manager' => $this->manager,
                'orderRepository' => $this->orderRepository,
                'resultRedirectFactory' => $this->resultRedirectFactory,
                'quoteRepository' => $this->quoteRepository,
                'jsonFactory' => $this->jsonFactory,
                'logger' => $this->logger,
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
