<?php
namespace Simpl\Checkout\Test\Unit\Controller\Cart;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Controller\Cart\Restore
 */
class RestoreTest extends TestCase
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
     * Mock httpRequest
     *
     * @var \Magento\Framework\App\Request\Http|PHPUnit\Framework\MockObject\MockObject
     */
    private $httpRequest;

    /**
     * Mock logger
     *
     * @var \Simpl\Checkout\Logger\Logger|PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

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
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Controller\Cart\Restore
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
        $this->httpRequest = $this->createMock(\Magento\Framework\App\Request\Http::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->orderFactoryInstance = $this->createMock(\Magento\Sales\Model\Order::class);
        $this->orderFactory = $this->createMock(\Magento\Sales\Model\OrderFactory::class);
        $this->orderFactory->method('create')->willReturn($this->orderFactoryInstance);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Controller\Cart\Restore::class,
            [
                'checkoutSession' => $this->checkoutSession,
                'manager' => $this->manager,
                'orderRepository' => $this->orderRepository,
                'resultRedirectFactory' => $this->resultRedirectFactory,
                'quoteRepository' => $this->quoteRepository,
                'httpRequest' => $this->httpRequest,
                'logger' => $this->logger,
                'orderFactory' => $this->orderFactory,
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
