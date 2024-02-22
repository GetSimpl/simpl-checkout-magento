<?php

namespace Simpl\Checkout\Block\Adminhtml\Order;

use Magento\Shipping\Helper\Data as ShippingHelper;
use Magento\Tax\Helper\Data as TaxHelper;
use Simpl\Checkout\Model\SimplFactory;
use Simpl\Checkout\Model\ResourceModel\Simpl as SimplResource;
use Magento\Sales\Block\Adminhtml\Order\View\Tab\Info as TabInfo;

class Info extends TabInfo
{
    protected $simplFactory;

    protected $simplResource;

    private $simplModel;

    public function __construct(
        SimplFactory $simplFactory,
        SimplResource $simplResource,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        array $data = [],
        ?ShippingHelper $shippingHelper = null,
        ?TaxHelper $taxHelper = null
    ) {
        $this->simplFactory = $simplFactory;
        $this->simplResource = $simplResource;
        parent::__construct($context, $registry, $adminHelper, $data, $shippingHelper, $taxHelper);
    }

    public function getTransactionId($id)
    {
        if ($this->simplModel) {
            return $this->simplModel->getTransactionId();
        }
        $simpl = $this->simplFactory->create();
        $this->simplResource->load($simpl, $id, 'order_id');
        $this->simplModel = $simpl;
        return $this->simplModel->getTransactionId();
    }

    public function getPaymentMode($id)
    {
        if ($this->simplModel) {
            return $this->simplModel->getPaymentMethod();
        }
        $simpl = $this->simplFactory->create();
        $this->simplResource->load($simpl, $id, 'order_id');
        $this->simplModel = $simpl;
        return $this->simplModel->getPaymentMethod();
    }
}
