<?php

namespace Simpl\Checkout\Model\Adminhtml\Source;

class Mode {
    const TEST = 'test';
    const LIVE = 'live';

    public function toOptionArray() {
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
