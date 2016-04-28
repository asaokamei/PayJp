<?php
namespace AsaoKamei\PayJp;

class ChargeFactory
{
    /**
     * @var PayJpApi
     */
    private $api_key;

    /**
     * @param PayJpApi $api
     */
    public function __construct($api)
    {
        $this->api_key = $api;
    }

    /**
     * @param string $api_key
     * @param string $currency
     * @return ChargeFactory
     */
    public static function forge($api_key, $currency = 'jpy')
    {
        return new self(new PayJpApi($api_key, $currency));
    }

    /**
     * @param string $token_id
     * @return CreatePay
     */
    public function create($token_id)
    {
        return new CreatePay($this->api_key, $token_id);
    }

    /**
     * @param $charge_id
     * @return CreatePay
     */
    public function retrieve($charge_id)
    {
        return new UpdatePay($this->api_key, $charge_id);
    }
}