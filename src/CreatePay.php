<?php
namespace AsaoKamei\PayJp;

use Payjp\Charge;

class CreatePay extends AbstractPayJp
{
    /**
     * @var int
     */
    private $expiry_days = null;

    /**
     * @var Charge
     */
    public $charge;

    /**
     * @var string
     */
    private $token_id;

    /**
     * @param PayJpApi $api
     * @param string   $charge_id
     */
    public function __construct($api, $charge_id)
    {
        parent::__construct($api);
        $this->token_id = $charge_id;
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
        $days = (int)$days;
        if ($days < 1) {
            throw new \InvalidArgumentException('Expiration day cannot be less than 1 day');
        }
        if ($days > 60) {
            throw new \InvalidArgumentException('Expiration day cannot be larger than 60 days.');
        }
        $this->expiry_days = $days;
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
        return $this->createPayment($amount, true);
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
        return $this->createPayment($amount, false, $this->expiry_days);
    }

    /**
     * @param int      $amount
     * @param bool     $captured
     * @param int|null $expire
     * @return bool|string
     */
    private function createPayment($amount, $captured, $expire = null)
    {
        $parameter = [
            "card"     => $this->token_id,
            "amount"   => $amount,
            "currency" => $this->payJp->getCurrency(),
        ];
        if (!$captured) {
            $parameter['capture'] = 'false';
        }
        if (isset($expire)) {
            $parameter['expiry_days'] = $expire;
        }
        $charge = $this->payJp->createCharge($parameter);
        if (isset($charge['error'])) {
            return $this->setError($charge['error']);
        }
        if (!isset($charge['id'])) {
            return $this->setError([
                                       "message" => "id not returned.",
                                   ]);
        }
        $this->charge = $charge;
        return $charge['id'];
    }
}