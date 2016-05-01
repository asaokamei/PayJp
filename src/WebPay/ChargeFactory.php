<?php
namespace AsaoKamei\PayJp\WebPay;

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use AsaoKamei\PayJp\Interfaces\CreatePayInterface;
use AsaoKamei\PayJp\PayJp\UpdatePay;
use WebPay\WebPay;

class ChargeFactory implements ChargeFactoryInterface
{
    /**
     * @var WebPay
     */
    private $web_pay;

    /**
     * @var string
     */
    private $currency;

    /**
     * ChargeFactory constructor.
     *
     * @param WebPay $web_pay
     * @param string $currency
     */
    public function __construct($web_pay, $currency = 'jpy')
    {
        $this->web_pay  = $web_pay;
        $this->currency = $currency;
    }

    /**
     * @param string $api_key
     * @param string $currency
     * @return self
     */
    public static function forge($api_key, $currency = 'jpy')
    {
        new self(new WebPay($api_key), $currency);
    }

    /**
     * @param string $card_id
     * @return CreatePayInterface
     */
    public function create($card_id)
    {
        return new CreateCharge($this->web_pay, $card_id, $this->currency);
    }

    /**
     * @param $charge_id
     * @return UpdatePay
     */
    public function retrieve($charge_id)
    {
        $this->web_pay->charge->retrieve($charge_id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return UpdatePay[]
     */
    public function getList($limit = 10, $offset = 0)
    {
        // TODO: Implement getList() method.
    }
}