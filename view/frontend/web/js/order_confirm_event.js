require(
    [
    'jquery',
    'mage/url',
    'mage/storage'
    ],
    function (
    $,
    url,
    storage
    ) {
        'use strict';
        $(function() {
            var eventUrl = window.BASE_URL + 'simpl/payment/event';
            var eventType = "checkout_pageview";
            var eventTypeOrderConfirm = "order_confirm";
            const checkoutSuccessPage = /checkout\/onepage\/success/gm;

            if ((checkoutSuccessPage.exec(window.location.href)) !== null) {
                eventType = "thank_you_pageview";

                var orderConfirmPayload = JSON.stringify({
                        event: eventTypeOrderConfirm,
                        page_url: window.location.href
                    }
                );

                storage.post(
                    eventUrl,
                    orderConfirmPayload
                );
            }

            var payload = JSON.stringify({
                event: eventType,
                page_url: window.location.href
                }
            );

            storage.post(
                eventUrl,
                payload
            );
        });
    }
);
