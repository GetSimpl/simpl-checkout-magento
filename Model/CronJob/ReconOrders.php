<?php

namespace Simpl\Checkout\Model\CronJob;

use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Simpl\Checkout\Helper\Config;
use Magento\Sales\Model\Order;

/**
 * Class that provides functionality of cleaning expired quotes by cron
 */
class ReconOrders
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
     * @param OrderManagementInterface $orderManagement
     */
    public function __construct(
        Config $config,
        CollectionFactory $collectionFactory,
        OrderManagementInterface $orderManagement
    ) {
        $this->config = $config;
        $this->orderCollectionFactory = $collectionFactory;
        $this->orderManagement = $orderManagement;
    }

    /**
     * Cancel payment pending orders (cron process)
     *
     * @return void
     */
    public function execute()
    {
        $lifetime = $this->config::PENDING_ORDER_LIFE_TIME;
        $newOrderState = Order::STATE_NEW;
        $orders = $this->orderCollectionFactory->create();
        $orders->getSelect()->joinLeft(
            ['payment' => 'sales_order_payment'],
            'main_table.entity_id = payment.parent_id',
            'method'
        );
        $orders->getSelect()->joinLeft(
            ['simpl_order' => 'simpl_order_details'],
            'main_table.entity_id = simpl_order.order_id',
            'payment_method'
        );
        $orders->addFieldToFilter('main_table.state', $newOrderState);
        $orders->addFieldToFilter('payment.method', $this->config::KEY_PAYMENT_CODE);
        $orders->getSelect()->where(
            new \Zend_Db_Expr('TIME_TO_SEC(TIMEDIFF(CURRENT_TIMESTAMP, `updated_at`)) >= ' . $lifetime * 60)
        );
        $orders->getSelect()->where('simpl_order.payment_method IS NULL');

        foreach ($orders->getAllIds() as $entityId) {
            $this->orderManagement->cancel((int) $entityId);
        }
    }
}
