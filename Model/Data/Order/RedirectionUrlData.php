<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface;

class RedirectionUrlData implements RedirectionUrlDataInterface
{
    private $redirectionUrl;

    /**
     * @return string
     */
    public function getRedirectionUrl() {
        return $this->redirectionUrl;
    }

    /**
     * @param string $redirectionUrl
     * @return $this
     */
    public function setRedirectionUrl($redirectionUrl) {
        $this->redirectionUrl = $redirectionUrl;
        return $this;
    }
}
