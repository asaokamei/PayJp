<?php
namespace tests\Tools;

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use AsaoKamei\PayJp\PayJp\ChargeFactory;
use RuntimeException;

class Services
{
    private $init;

    private $service;

    private $info;

    public function __construct()
    {
        $file       = dirname(__DIR__) . '/services.ini';
        $this->init = parse_ini_file($file, true);
    }

    /**
     * @param string $service
     */
    public function setService($service)
    {
        if (!isset($this->init[$service])) {
            throw new RuntimeException;
        }
        $this->service = $service;
        $this->info    = $this->init[$service];
    }

    /**
     * @param string $key
     * @return null|string
     */
    private function _get($key)
    {
        return array_key_exists($key, $this->info) ? $this->info[$key] : null;
    }

    /**
     * @return null|string
     */
    public function getPublicKey()
    {
        return $this->_get('public_key');
    }

    /**
     * @return null|string
     */
    public function getSecretKey()
    {
        return $this->_get('secret_key');
    }

    /**
     * @return null|string
     */
    public function getFactoryClass()
    {
        return $this->_get('factory');
    }

    /**
     * @return ChargeFactoryInterface[]
     */
    public function getFactories()
    {
        $factories = [];
        foreach($this->init as $service => $info) {
            $this->setService($service);
            /** @var ChargeFactory $class */
            $class = $this->getFactoryClass();
            $factories[$service] = $class::forge($this->getSecretKey());
        }
        return $factories;
    }
}