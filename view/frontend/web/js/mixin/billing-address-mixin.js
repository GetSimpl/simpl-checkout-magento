define([
    'jquery',
    'ko',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/url',
    'mage/storage'
], function ($, ko, quote, fullScreenLoader, url, storage) {
    'use strict';

    return function (billingAddress) {
        return billingAddress.extend({
            updateAddress: function () {

                const eventUrl = url.build('simpl/payment/event');
                const payload = JSON.stringify({
                        event: "platform_payment_address_update",
                        page_url: window.location.href
                    }
                );
                storage.post(
                    eventUrl,
                    payload
                );

                this._super();
            }
        });
    };
});
