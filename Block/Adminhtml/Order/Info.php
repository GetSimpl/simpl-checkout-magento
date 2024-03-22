<?php

namespace Simpl\Checkout\Block\Adminhtml\Order;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Helper\Admin;
use Magento\Shipping\Helper\Data as ShippingHelper;
use Magento\Tax\Helper\Data as TaxHelper;
use Simpl\Checkout\Model\SimplOrderFactory;
use Simpl\Checkout\Model\ResourceModel\SimplOrder as SimplResource;
use Magento\Sales\Block\Adminhtml\Order\View\Tab\Info as TabInfo;

class Info extends TabInfo
{
    /**
     * @var SimplOrderFactory
     */
    protected $simplFactory;
    /**
     * @var SimplResource
     */
    protected $simplResource;

    /**
     * @param SimplOrderFactory $simplFactory
     * @param SimplResource $simplResource
     * @param Context $context
     * @param Registry $registry
     * @param Admin $adminHelper
     * @param array $data
     * @param ShippingHelper|null $shippingHelper
     * @param TaxHelper|null $taxHelper
     */
    public function __construct(
        SimplOrderFactory $simplFactory,
        SimplResource $simplResource,
        Context $context,
        Registry $registry,
        Admin $adminHelper,
        array $data = [],
        ?ShippingHelper $shippingHelper = null,
        ?TaxHelper $taxHelper = null
    ) {
        $this->simplFactory = $simplFactory;
        $this->simplResource = $simplResource;
        parent::__construct($context, $registry, $adminHelper, $data, $shippingHelper, $taxHelper);
    }

    /**
     * To retrieve transaction id based on id
     *
     * @param int|string $id
     * @return mixed
     */
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

    /**
     * To retrieve payment mode with id
     *
     * @param int|string $id
     * @return mixed
     */
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
