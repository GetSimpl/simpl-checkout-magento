<?php

namespace Simpl\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Simpl\Checkout\Model\ResourceModel\SimplOrder as ResourceModel;

class SimplOrder extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'simpl_order_details_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
