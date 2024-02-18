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
            var eventUrl = url.build('simpl/payment/event')
            var eventType = "platform_checkout_pageview";
            const checkoutSuccessPage = /checkout\/onepage\/success/gm;

            if ((checkoutSuccessPage.exec(window.location.href)) !== null) {
                eventType = "platform_thankyou_pageview";
            }

            var payload = JSON.stringify({
                event: eventType
                }
            );

            storage.post(
                eventUrl,
                payload
            );
        });
    }
);
