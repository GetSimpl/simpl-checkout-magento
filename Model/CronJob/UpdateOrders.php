<?php

namespace Simpl\Checkout\Model\CronJob;

use Magento\Framework\App\ObjectManager;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Simpl\Checkout\Helper\Config;

/**
 * Class that provides functionality of cleaning expired quotes by cron
 */
class UpdateOrders
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;

    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;

    /**
     * @param Config $config
     * @param CollectionFactory $collectionFactory
     * @param OrderManagementInterface|null $orderManagement
     */
    public function __construct(
        Config $config,
        CollectionFactory $collectionFactory,
        OrderManagementInterface $orderManagement = null
    ) {
        $this->config = $config;
        $this->orderCollectionFactory = $collectionFactory;
        $this->orderManagement = $orderManagement ?: ObjectManager::getInstance()->get(OrderManagementInterface::class);
    }

    /**
     * Cancel payment pending orders (cron process)
     *
     * @return void
     */
    public function execute()
    {
        $lifetime = $this->config::PENDING_ORDER_LIFE_TIME;
        $newOrderStatus = $this->config->getNewOrderStatus();
        $orders = $this->orderCollectionFactory->create();
        $orders->getSelect()->joinLeft(
            ['payment' => 'sales_order_payment'],
            'main_table.entity_id = payment.parent_id',
            'method'
        );
        $orders->getSelect()->joinLeft(
            ['simpl' => 'simpl_checkout'],
            'main_table.entity_id = simpl.order_id',
            'payment_method'
        );
        $orders->addFieldToFilter('main_table.status', $newOrderStatus);
        $orders->addFieldToFilter('payment.method', $this->config::KEY_PAYMENT_CODE);
        $orders->getSelect()->where(
            new \Zend_Db_Expr('TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, `updated_at`)) >= ' . $lifetime * 60)
        );
        $orders->getSelect()->where('simpl.payment_method IS NULL');

        foreach ($orders->getAllIds() as $entityId) {
            $this->orderManagement->cancel((int) $entityId);
        }
    }
}
