<?php
namespace AsaoKamei\PayJp;

abstract class AbstractPayJp
{
    /**
     * @var PayJpApi
     */
    protected $payJp;

    /**
     * @var bool
     */
    private $isOK = true;

    /**
     * @var array
     */
    private $error = [];

    /**
     * AbstractPayJp constructor.
     *
     * @param PayJpApi    $api
     */
    public function __construct($api)
    {
        $this->payJp = $api;
    }

    /**
     * @return bool
     */
    public function isOK()
    {
        return $this->isOK;
    }

    /**
     * @return array
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param array $error
     * @return bool
     */
    protected function setError(array $error)
    {
        $this->isOK  = false;
        $this->error = $error;
        return false;
    }
}