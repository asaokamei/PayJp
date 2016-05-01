<?php
namespace AsaoKamei\PayJp\PayJp;

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
     * @var ErrorMessages
     */
    private $errorMessages;

    /**
     * AbstractPayJp constructor.
     *
     * @param PayJpApi $api
     */
    public function __construct($api)
    {
        $this->errorMessages = new ErrorMessages();
        $this->payJp         = $api;
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
     * @return null|string
     */
    public function getMessage()
    {
        if (!isset($this->error)) {
            return '';
        }
        if ($this->errorMessages->has($this->g('code'))) {
            return $this->errorMessages->get($this->g('code'));
        }
        if ($this->g('code') === 0 &&
            preg_match('/^token `[_0-9a-zA-Z]+` has already been used\.$/i', $this->g('message'))) {
            return "すでにカード情報は使われています";
        }
        if ($this->g('message')) {
            return $this->g('message');
        }
        return '支払い処理中にエラーが起こりました';
    }

    /**
     * @param $code
     * @return null
     */
    private function g($code)
    {
        return array_key_exists($code, $this->error) ? $this->error[$code] : null;
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