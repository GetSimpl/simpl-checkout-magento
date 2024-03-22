define(
    [
        'jquery',
        'Magento_Checkout/js/view/payment/default',
        'Magento_Checkout/js/action/redirect-on-success',
        'mage/url',
        'mage/storage',
        'Magento_Checkout/js/model/payment/additional-validators',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (
        $,
        Component,
        redirectOnSuccessAction,
        url,
        storage,
        additionalValidators,
        fullScreenLoader
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
            getButtonLabel: function () {
                return window.checkoutConfig.payment.simplcheckout.button_label;
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
                                    // Relook this in future, since performance marketing event can be impacted because of this.
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
             * Error handler function
             */
            errorHandler: function (error) {
                var errorMessage = error.message || 'An error occurred';
                fullScreenLoader.stopLoader();
                $('#payment-error-message').text(errorMessage).show();
            },
            /**
             * After place order callback
             */
            initSimplCheckout: function () {
                var self = this;
                var paymentInit = url.build('simpl/payment/init');
                fullScreenLoader.startLoader();
                storage.get(
                    paymentInit
                ).fail(
                    function (response) {

                        var error = {
                            message: 'Payment Init failed. Please try again.'
                        };
                        self.errorHandler(error);
                        self.restoreCart();
                    }
                ).done(
                    function (response) {
                        if (response.status !== 'error') {
                            window.location.replace(response.url);
                        } else {
                            var error = {
                                message: 'Payment Init failed. Please try again.'
                            };
                            self.errorHandler(error);
                            self.restoreCart();
                        }
                    }
                ).always(
                    function (response) {
                        fullScreenLoader.stopLoader();
                    }
                );
            },
            /**
             * Function to restore cart if payment failed.
             */
            restoreCart: function () {
                var restoreCartUrl = url.build('simpl/payment/restoreinitfailure');
                var cartUrl = url.build('checkout/cart/');
                fullScreenLoader.startLoader();
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
                ).always(
                    function (response) {
                        fullScreenLoader.stopLoader();
                    }
                );
            },
        });
    }
);
