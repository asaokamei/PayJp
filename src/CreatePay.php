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
     * @param string $api_key
     * @return CreatePay
     */
    public static function forge($api_key)
    {
        return new self(new PayJpApi($api_key));
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
     * @param string $card_token
     * @param int    $amount
     * @return bool|string
     */
    public function charge($card_token, $amount)
    {
        return $this->createPayment($card_token, $amount, true);
    }

    /**
     * カードトークンから指定した金額を与信（オーソリ）する。
     * 成功したら支払いIDを返す。
     *
     * @param string $card_token
     * @param int    $amount
     * @return bool|string
     */
    public function authorize($card_token, $amount)
    {
        return $this->createPayment($card_token, $amount, false, $this->expiry_days);
    }

    /**
     * @param string   $card_token
     * @param int      $amount
     * @param bool     $captured
     * @param int|null $expire
     * @return bool|string
     */
    private function createPayment($card_token, $amount, $captured, $expire = null)
    {
        $parameter = [
            "card"     => $card_token,
            "amount"   => $amount,
            "currency" => $this->payJp->getCurrency(),
            "captured" => $captured,
        ];
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