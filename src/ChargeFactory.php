<?php
namespace AsaoKamei\PayJp;

use Payjp\Charge;
use Payjp\Collection;

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
     * @return UpdatePay
     */
    public function retrieve($charge_id)
    {
        return new UpdatePay($this->api_key, $charge_id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return UpdatePay[]
     */
    public function getList($limit = 10, $offset = 0)
    {
        $list = $this->api_key->getChargeList($limit, $offset);
        $found = [];
        for($i = 0; $i < $list['count']; $i++) {
            $found[] = new UpdatePay($this->api_key, null, $list['data'][$i]);
        }
        return $found;
    }
}