<?php

namespace Simpl\Checkout\Model\Data;

class RedirectionUrlData implements \Simpl\Checkout\Api\Data\RedirectionUrlDataInterface
{
    private $redirectionUrl;

    /**
     * @return string
     */
    public function getRedirectionUrl()
    {
        return $this->redirectionUrl;
    }

    /**
     * @param string $redirectionUrl
     * @return $this
     */
    public function setRedirectionUrl($redirectionUrl)
    {
        $this->redirectionUrl = $redirectionUrl;
        return $this;
    }
}
