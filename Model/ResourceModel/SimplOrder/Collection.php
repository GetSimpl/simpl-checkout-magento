<?php

namespace Simpl\Checkout\Model\ResourceModel\SimplOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Simpl\Checkout\Model\ResourceModel\SimplOrder as ResourceModel;
use Simpl\Checkout\Model\SimplOrder as Model;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'simpl_order_details_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
