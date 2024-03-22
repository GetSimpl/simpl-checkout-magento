<?php

namespace Simpl\Checkout\Model\Config;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Config\Block\System\Config\Form\Field;

class Disable extends Field
{
    /**
     * Disable the input field.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $element->setDisabled('disabled');
        return $element->getElementHtml();
    }
}
