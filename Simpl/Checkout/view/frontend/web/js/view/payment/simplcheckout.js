define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'simplcheckout',
                component: 'Simpl_Checkout/js/view/payment/method-renderer/simplcheckout-method'
            }
        );
        return Component.extend({});
    }
);