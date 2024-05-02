<?php

namespace Simpl\Checkout\Model\Data\Order;

use Simpl\Checkout\Api\Data\Order\RedirectionUrlDataInterface;

class RedirectionUrlData implements RedirectionUrlDataInterface
{
    /**
     * @var string
     */
    private $redirectionUrl;

    /**
     * Gets redirect URL from the API response data.
     *
     * @return string
     */
    public function getRedirectionUrl()
    {
        return $this->redirectionUrl;
    }

    /**
     * Sets redirect URL in the API response data.
     *
     * @param string $redirectionUrl
     * @return $this
     */
    public function setRedirectionUrl($redirectionUrl)
    {
        $this->redirectionUrl = $redirectionUrl;
        return $this;
    }
}
