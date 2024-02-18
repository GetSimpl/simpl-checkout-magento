var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/select-payment-method': {
                'Simpl_Checkout/js/mixin/select-payment-method-mixin': true
            },
            'Magento_Checkout/js/view/billing-address': {
                'Simpl_Checkout/js/mixin/billing-address-mixin': true
            }
        }
    }
};
