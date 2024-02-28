var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/select-payment-method': {
                'Simpl_Checkout/js/mixin/payment-info-submitted-event-mixin': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'Simpl_Checkout/js/mixin/address-info-submitted-event-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Simpl_Checkout/js/mixin/payment-initiate-event-mixin': true
            }
        }
    }
};
