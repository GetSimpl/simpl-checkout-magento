<?php

namespace Simpl\Checkout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class SimplOrder extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'simpl_order_details_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('simpl_order_details', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
