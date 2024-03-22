<?php

namespace Simpl\Checkout\Controller\Payment;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Simpl\Checkout\Events\CheckoutPageview;
use Simpl\Checkout\Events\PaymentInitiate;
use Simpl\Checkout\Events\AddressInfoSubmitted;
use Simpl\Checkout\Events\OrderConfirm;
use Simpl\Checkout\Events\PaymentInfoSubmitted;
use Simpl\Checkout\Events\ThankyouPageview;

class Event implements HttpPostActionInterface
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;
    /**
     * @var CheckoutPageview
     */
    protected $checkoutPageview;
    /**
     * @var PaymentInitiate
     */
    protected $paymentInitiate;
    /**
     * @var AddressInfoSubmitted
     */
    protected $addressInfoSubmitted;
    /**
     * @var OrderConfirm
     */
    protected $orderConfirm;
    /**
     * @var PaymentInfoSubmitted
     */
    protected $paymentInfoSubmitted;
    /**
     * @var ThankyouPageview
     */
    protected $thankyouPageview;
    /**
     * @var Http
     */
    protected $request;
    /**
     * @var Json
     */
    protected $json;

    /**
     *
     *
     * @param Http $request
     * @param Json $json
     * @param JsonFactory $jsonFactory
     * @param CheckoutPageview $checkoutPageview
     * @param PaymentInitiate $paymentInitiate
     * @param AddressInfoSubmitted $addressInfoSubmitted
     * @param OrderConfirm $orderConfirm
     * @param PaymentInfoSubmitted $paymentInfoSubmitted
     * @param ThankyouPageview $thankyouPageview
     */
    public function __construct(
        Http $request,
        Json $json,
        JsonFactory $jsonFactory,
        CheckoutPageview $checkoutPageview,
        PaymentInitiate $paymentInitiate,
        AddressInfoSubmitted $addressInfoSubmitted,
        OrderConfirm $orderConfirm,
        PaymentInfoSubmitted  $paymentInfoSubmitted,
        ThankyouPageview $thankyouPageview
    ) {
        $this->request = $request;
        $this->json = $json;
        $this->jsonFactory = $jsonFactory;
        $this->checkoutPageview = $checkoutPageview;
        $this->paymentInitiate = $paymentInitiate;
        $this->addressInfoSubmitted = $addressInfoSubmitted;
        $this->orderConfirm = $orderConfirm;
        $this->paymentInfoSubmitted = $paymentInfoSubmitted;
        $this->thankyouPageview = $thankyouPageview;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $data["status"] = false;

        $event = $this->getParam('event');

        switch ($event) {
            case "checkout_pageview":
                $this->checkoutPageview->handle();
                break;

            case "payment_info_submitted":
                $this->paymentInfoSubmitted->handle();
                break;

            case "address_info_submitted":
                $this->addressInfoSubmitted->handle();
                break;

            case "payment_initiate":
                $this->paymentInitiate->handle();
                break;

            case "order_confirm":
                $this->orderConfirm->handle();
                break;

            case "thank_you_pageview":
                $this->thankyouPageview->handle();
                break;
        }

        $data["status"] = true;
        $resultJson = $this->jsonFactory->create();
        $resultJson->setData($data);
        return $resultJson;
    }

    /**
     * To get the param passed as body request.
     *
     * @param string $key
     */
    public function getParam($key)
    {
        $content = $this->json->unserialize($this->request->getContent());
        if (isset($content[$key])) {
            return $content[$key];
        }
        return null;
    }
}
