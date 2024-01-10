<?php

namespace Simpl\Checkout\Api\Data;

interface RedirectionUrlDataInterface
{
    /**
     * Get version
     *
     * @return string|null
     */
    public function getRedirectionUrl();

    /**
     * Set version
     *
     * @param string $redirectionUrl
     * @return $this
     */
    public function setRedirectionUrl($redirectionUrl);
}
