define(
    [
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/redirect-on-success',
        'mage/url',
        'mage/storage',
        'Magento_Checkout/js/model/payment/additional-validators'
    ],
    function (
        Component,
        redirectOnSuccessAction,
        url,
        storage,
        additionalValidators
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Simpl_Checkout/payment/simplcheckout'
            },
            redirectAfterPlaceOrder: false,
            orderId: null,
            getInstructions: function () {
                return window.checkoutConfig.payment.simplcheckout.instructions;
            },
            getTitleForFrontend: function () {
                return window.checkoutConfig.payment.simplcheckout.title_for_frontend;
            },
            /**
             * Place order.
             */
            placeOrder: function (data, event) {
                var self = this;

                if (event) {
                    event.preventDefault();
                }

                if (!self.orderId) {
                    if (this.validate() &&
                        additionalValidators.validate() &&
                        this.isPlaceOrderActionAllowed() === true
                    ) {
                        this.isPlaceOrderActionAllowed(false);

                        this.getPlaceOrderDeferredObject()
                            .done(
                                function (orderId) {
                                    self.orderId =  orderId;
                                    self.afterPlaceOrder();
                                    self.initSimplCheckout();
                                }
                            ).always(
                            function () {
                                self.isPlaceOrderActionAllowed(true);
                            }
                        );

                        return true;
                    }
                } else {
                    self.initSimplCheckout();
                }

                return false;
            },
            /**
             * After place order callback
             */
            initSimplCheckout: function () {
                var self = this;
                var paymentInit = url.build('simpl/payment/init');
                storage.get(
                    paymentInit
                ).fail(
                    function (response) {
                        self.restoreCart();
                    }
                ).done(
                    function (response) {
                        if (response.status !== 'error') {
                            window.location.replace(response.url);
                        } else {
                            self.restoreCart();
                        }
                    }
                );
            },
            /**
             * Function to restore cart if payment failed.
             */
            restoreCart: function () {
                var restoreCartUrl = url.build('simpl/payment/restore');
                var cartUrl = url.build('checkout/cart/');
                storage.get(
                    restoreCartUrl
                ).fail(
                    function (response) {
                        window.location.replace(cartUrl);
                    }
                ).done(
                    function (response) {
                        if (response.status) {
                            window.location.replace(url.build(response.url));
                        } else {
                            window.location.replace(cartUrl);
                        }
                    }
                );
            },
        });
    }
);
