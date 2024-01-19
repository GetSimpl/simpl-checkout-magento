<?php

namespace Simpl\Checkout\Api\Data;

interface ApiResponseDataInterface
{
    /**
     * Get success status
     *
     * @return bool
     */
    public function getSuccess();

    /**
     * Set success status
     *
     * @param bool $success
     * @return $this
     */
    public function setSuccess($success);

    /**
     * Get version
     *
     * @return string|null
     */
    public function getVersion();

    /**
     * Set version
     *
     * @param string $version
     * @return $this
     */
    public function setVersion($version);

    /**
     * Get version
     *
     * @return \Simpl\Checkout\Api\Data\ErrorDataInterface|null
     */
    public function getError();

    /**
     * Set version
     *
     * @param \Simpl\Checkout\Api\Data\ErrorDataInterface $error
     * @return $this
     */
    public function setError($error);

    /**
     * Get success status
     *
     * @return mixed
     */
    public function getData();

    /**
     * Set success status
     *
     * @param mixed
     * @return $this
     */
    public function setData($data);
}
