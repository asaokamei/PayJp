<?php
namespace AsaoKamei\PayJp\WebPay;

use AsaoKamei\PayJp\Interfaces\CreatePayInterface;
use WebPay\Charge;
use WebPay\WebPay;


class CreateCharge implements CreatePayInterface
{
    /**
     * @var int
     */
    private $expire_days;

    /**
     * @var WebPay
     */
    private $web_pay;

    /**
     * @var string
     */
    private $currency;

    /**
     * @var string
     */
    private $card_id;

    /**
     * @var Charge
     */
    private $charge;

    public function __construct($web_pay, $card_id, $currency = 'jpy')
    {
        $this->web_pay  = $web_pay;
        $this->card_id  = $card_id;
        $this->currency = $currency;
    }

    /**
     * 与信（オーソリ）を行う場合の認証の期間を日数で指定する。
     * 有効な日数は１日〜６０日まで。
     *
     * @param int $days
     * @return $this
     */
    public function setExpireDays($days)
    {
        $this->expire_days = (int)$days;

        return $this;
    }

    /**
     * カードトークンから指定した金額を引き落とす。
     * 成功したら支払いIDを返す。
     *
     * @param int $amount
     * @return bool|string
     */
    public function charge($amount)
    {
        $this->charge = $this->getChargeObj()->create([
            "amount"   => $amount,
            "currency" => $this->currency,
            "card"     => $this->card_id,
        ]);
        return $this->charge->id;
    }

    /**
     * カードトークンから指定した金額を与信（オーソリ）する。
     * 成功したら支払いIDを返す。
     *
     * @param int $amount
     * @return bool|string
     */
    public function authorize($amount)
    {
        $parameter = [
            "amount"   => $amount,
            "currency" => $this->currency,
            "card"     => $this->card_id,
            'capture'  => false,
        ];
        if (isset($this->expire_days)) {
            $parameter['expire_days'] = $this->expire_days;
        }
        $this->charge = $this->getChargeObj()->create($parameter);
        
        return $this->charge->id;
    }

    /**
     * @return Charge
     */
    private function getChargeObj()
    {
        return $this->web_pay->charge;
    }
}