<?php

namespace Simpl\Checkout\Model\Adminhtml\Source;

class Mode
{
    public const TEST = 'test';
    public const LIVE = 'live';

    /**
     * To prepare array options for showing payment mod.
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::TEST,
                'label' => __('Test')
            ],
            [
                'value' => self::LIVE,
                'label' => __('Live')
            ],
        ];
    }
}
