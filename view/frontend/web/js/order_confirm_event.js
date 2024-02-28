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
            const checkoutSuccessPage = /checkout\/onepage\/success/gm;

            if ((checkoutSuccessPage.exec(window.location.href)) !== null) {
                eventType = "thank_you_pageview";
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
