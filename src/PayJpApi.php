<?php
namespace AsaoKamei\PayJp;

use ArrayAccess;
use Payjp\Charge;
use Payjp\Payjp;

class PayJpApi
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @param string $api_key
     * @param string $currency
     */
    public function __construct($api_key, $currency = 'jpy')
    {
        Payjp::setApiKey($api_key);
        $this->currency = $currency;
    }

    /**
     * @param array $parameter
     * @return Charge|ArrayAccess
     */
    public function createCharge(array $parameter)
    {
        return Charge::create($parameter);
    }

    /**
     * @param string $pay_id
     * @return Charge|ArrayAccess
     */
    public function retrieveCharge($pay_id)
    {
        return Charge::retrieve($pay_id);
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }
}