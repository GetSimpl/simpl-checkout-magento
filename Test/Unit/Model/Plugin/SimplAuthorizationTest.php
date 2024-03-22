<?php
namespace Simpl\Checkout\Test\Unit\Model\Plugin;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Plugin\SimplAuthorization
 */
class SimplAuthorizationTest extends TestCase
{
    /**
     * Mock authHelper
     *
     * @var \Simpl\Checkout\Helper\AuthHelper|PHPUnit\Framework\MockObject\MockObject
     */
    private $authHelper;

    /**
     * Mock request
     *
     * @var \Magento\Framework\HTTP\PhpEnvironment\Request|PHPUnit\Framework\MockObject\MockObject
     */
    private $request;

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
     * @var \Simpl\Checkout\Model\Plugin\SimplAuthorization
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->authHelper = $this->createMock(\Simpl\Checkout\Helper\AuthHelper::class);
        $this->request = $this->createMock(\Magento\Framework\HTTP\PhpEnvironment\Request::class);
        $this->logger = $this->createMock(\Simpl\Checkout\Logger\Logger::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Plugin\SimplAuthorization::class,
            [
                'authHelper' => $this->authHelper,
                'request' => $this->request,
                'logger' => $this->logger,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestAroundIsAllowed()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestAroundIsAllowed
     */
    public function testAroundIsAllowed(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
