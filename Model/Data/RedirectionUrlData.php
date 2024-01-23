<?php

namespace Simpl\Checkout\Model\Data;

use Simpl\Checkout\Api\Data\RedirectionUrlDataInterface;

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
