define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/redirect-on-success',
        'mage/url',
        'mage/storage'
    ],
    function (Component, redirectOnSuccessAction, url, storage) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Simpl_Checkout/payment/simplcheckout'
            },
            redirectAfterPlaceOrder: false,
            getInstructions: function () {
                return window.checkoutConfig.payment.simplcheckout.instructions;
            },
            /**
             * After place order callback
             */
            afterPlaceOrder: function () {
                var paymentInit = url.build('simpl/payment/init');
                storage.get(
                    paymentInit
                ).fail(
                    function (response) {
                        //TODO TO HANDLE IF PAYMENT INIT REQUEST FAIL
                    }
                ).done(
                    function (response) {
                        if (response.status !== 'error') {
                            window.location.replace(response.url);
                        }
                    }
                );
            },
        });
    }
);
