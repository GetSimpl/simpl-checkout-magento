<?php

namespace Simpl\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Simpl\Checkout\Model\ResourceModel\Simpl as ResourceModel;

class Simpl extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'simpl_checkout_model';

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
