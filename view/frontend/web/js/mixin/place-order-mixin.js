define([
    'mage/url',
    'mage/storage',
    'mage/utils/wrapper'
], function ( url, storage, wrapper) {
    'use strict';

    return function (placeOrderAction) {
        return wrapper.wrap(placeOrderAction, function (originalAction, paymentData, redirectOnSuccess) {

            const eventUrl = url.build('simpl/payment/event');
            const payload = JSON.stringify({
                    event: "platform_payment_initiate",
                    button_text: paymentData.method,
                    page_url: window.location.href
                }
            );
            storage.post(
                eventUrl,
                payload
            );

            return originalAction(paymentData, redirectOnSuccess);
        });
    };
});
