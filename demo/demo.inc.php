<?php

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use AsaoKamei\PayJp\PayJp\ChargeFactory AS PayJpFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';
date_default_timezone_set('Asia/Tokyo');

session_start();

class PayService
{
    const SERVICE_ID = 'demo-service';
    const API_KEY_ID = 'demo-api-key';
    const PUB_KEY_ID = 'demo-pub-key';
    
    const PAY_JP = 'payjp';
    const WEB_PAY = 'webpay';
    
    public $service;
    public $key;
    public $pub;
    
    private $isLoaded = false;

    /**
     * PayService constructor.
     */
    public function __construct()
    {
        $this->loadService();
    }

    /**
     * @param string $service
     * @param string $key
     * @param string $pub
     */
    public function setupService($service, $key, $pub)
    {
        $_SESSION[self::SERVICE_ID] = $service;
        $_SESSION[self::API_KEY_ID] = $key;
        $_SESSION[self::PUB_KEY_ID] = $pub;
        $this->loadService();
    }

    /**
     * 
     */
    private function loadService()
    {
        if (isset($_SESSION[self::SERVICE_ID])) {
            $this->service = $_SESSION[self::SERVICE_ID];
            $this->key     = $_SESSION[self::API_KEY_ID];
            $this->pub     = $_SESSION[self::PUB_KEY_ID];
            $this->isLoaded = true;
            return;
        }
        $this->isLoaded = false;
    }

    /**
     * @return ChargeFactoryInterface
     */
    public function getFactory()
    {
        if ($this->service == self::PAY_JP) {
            return PayJpFactory::forge($this->key);
        }
        if ($this->service == self::WEB_PAY) {
            return AsaoKamei\PayJp\WebPay\ChargeFactory::forge($this->key);
        }
        throw new InvalidArgumentException;
    }

    /**
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * @return string
     */
    public function getInfo()
    {
        return "
         <ul>
            <li>service: {$this->service}</li>
            <li>api-key: {$this->key}</li>
            <li>pub-key: {$this->pub}</li>
        </ul>";
    }

    /**
     * @param array $post
     * @return null|string
     */
    public function getCardIdInPost($post)
    {
        if ($this->service === self::PAY_JP) {
            return array_key_exists('payjp-token', $post) ? $post['payjp-token'] : null;
        }
        if ($this->service === self::WEB_PAY) {
            return array_key_exists('webpay-token', $post) ? $post['webpay-token'] : null;
        }
        throw new InvalidArgumentException;
    }
}


/**
 * @param string $key
 * @return bool|string
 */
function getIdInPost($key = 'id')
{
    if (isset($_POST) && isset($_POST[$key]) && preg_match('/^[_0-9a-zA-Z]+$/', $_POST[$key])) {
        return $_POST[$key];
    }

    return false;
}

/**
 * @param string $key
 * @return bool|string
 */
function getAmountInPost($key = 'amount')
{
    if (isset($_POST) && isset($_POST[$key]) && preg_match('/^[_0-9]+$/', $_POST[$key])) {
        return $_POST[$key];
    }

    return false;
}

/**
 * @param string $message
 * @param bool   $isOK
 * @return string
 */
function showMessage($message, $isOK = true)
{
    if (!$message) {
        return '';
    }
    if (!$isOK) {
        $message = "<span style='color: red'>{$message}</span>";
    }
    $message = "<bold>{$message}</bold><br/><br/>";
    return $message;
}
