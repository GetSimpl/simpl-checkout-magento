<?php

namespace Simpl\Checkout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Simpl extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'simpl_checkout_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('simpl_checkout', 'entity_id');
        $this->_useIsObjectNew = true;
    }
}
