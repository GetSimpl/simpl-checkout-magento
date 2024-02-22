<?php

namespace Simpl\Checkout\Model\ResourceModel\Simpl;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Simpl\Checkout\Model\ResourceModel\Simpl as ResourceModel;
use Simpl\Checkout\Model\Simpl as Model;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'simpl_checkout_collection';

    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
