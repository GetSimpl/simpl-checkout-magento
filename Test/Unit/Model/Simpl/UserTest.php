<?php
namespace Simpl\Checkout\Test\Unit\Model\Simpl;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * @covers \Simpl\Checkout\Model\Simpl\User
 */
class UserTest extends TestCase
{
    /**
     * Mock customerSession
     *
     * @var \Magento\Customer\Model\Session|PHPUnit\Framework\MockObject\MockObject
     */
    private $customerSession;

    /**
     * Mock checkoutSession
     *
     * @var \Magento\Checkout\Model\Session|PHPUnit\Framework\MockObject\MockObject
     */
    private $checkoutSession;

    /**
     * Object Manager instance
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * Object to test
     *
     * @var \Simpl\Checkout\Model\Simpl\User
     */
    private $testObject;

    /**
     * Main set up method
     */
    public function setUp() : void
    {
        $this->objectManager = new ObjectManager($this);
        $this->customerSession = $this->createMock(\Magento\Customer\Model\Session::class);
        $this->checkoutSession = $this->createMock(\Magento\Checkout\Model\Session::class);
        $this->testObject = $this->objectManager->getObject(
        \Simpl\Checkout\Model\Simpl\User::class,
            [
                'customerSession' => $this->customerSession,
                'checkoutSession' => $this->checkoutSession,
            ]
        );
    }

    /**
     * @return array
     */
    public function dataProviderForTestGetUserDetails()
    {
        return [
            'Testcase 1' => [
                'prerequisites' => ['param' => 1],
                'expectedResult' => ['param' => 1]
            ]
        ];
    }

    /**
     * @dataProvider dataProviderForTestGetUserDetails
     */
    public function testGetUserDetails(array $prerequisites, array $expectedResult)
    {
        $this->assertEquals($expectedResult['param'], $prerequisites['param']);
    }
}
