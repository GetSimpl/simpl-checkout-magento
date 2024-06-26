define(
    [
        'jquery',
        'mage/url',
        'mage/storage'
    ], function (
        $,
        url,
        storage
    ) {
        'use strict';

        return function (selectPaymentMethodAction) {
            return function (paymentMethod) {

                const eventUrl = url.build('simpl/payment/event');
                const payload = JSON.stringify({
                        event: "payment_info_submitted",
                        page_url: window.location.href
                    }
                );
                storage.post(
                    eventUrl,
                    payload
                );

                return selectPaymentMethodAction(paymentMethod);
            };
        };
    }
);
