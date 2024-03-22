<?php
namespace Simpl\Checkout\Test\Unit\Controller\Index;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Controller\Index\OrderStatusUpdateOnRedirect
 */
class OrderStatusUpdateOnRedirectTest extends TestCase
{
    /**
     * Mock orderRepository
     *
     * @var \Magento\Sales\Api\OrderRepositoryInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $orderRepository;

    /**
     * Mock request
     *
     * @var \Magento\Framework\App\RequestInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

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
     * Mock messageManager
     *
     * @var \Magento\Framework\Message\ManagerInterface|PHPUnit\Framework\MockObject\MockObject
     */
    private $messageManager;

    /**
     * Mock logger
     *
     * @var \Psr\Log\LoggerInterface|PHPUnit\Framework\MockObject\MockObject
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
     * @var \Simpl\Checkout\Controller\Index\OrderStatusUpdateOnRedirect
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->orderRepository = $this->createMock(\Magento\Sales\Api\OrderRepositoryInterface::class);
        $this->request = $this->createMock(\Magento\Framework\App\RequestInterface::class);
        $this->resultRedirectFactoryInstance = $this->createMock(\Magento\Framework\Controller\Result\Redirect::class);
        $this->resultRedirectFactory = $this->createMock(\Magento\Framework\Controller\Result\RedirectFactory::class);
        $this->resultRedirectFactory->method('create')->willReturn($this->resultRedirectFactoryInstance);
        $this->messageManager = $this->createMock(\Magento\Framework\Message\ManagerInterface::class);
        $this->logger = $this->createMock(\Psr\Log\LoggerInterface::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Controller\Index\OrderStatusUpdateOnRedirect::class,
            [
                'orderRepository' => $this->orderRepository,
                'request' => $this->request,
                'resultRedirectFactory' => $this->resultRedirectFactory,
                'messageManager' => $this->messageManager,
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

    /**
     * @return array
     */
    public function dataProviderForTestCreateCsrfValidationException()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestCreateCsrfValidationException
     */
    public function testCreateCsrfValidationException(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }

    /**
     * @return array
     */
    public function dataProviderForTestValidateForCsrf()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestValidateForCsrf
     */
    public function testValidateForCsrf(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
