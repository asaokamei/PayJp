<?php
namespace AsaoKamei\PayJp;

use Payjp\Charge;

class UpdatePay extends AbstractPayJp
{
    /**
     * @var Charge
     */
    public  $charge;

    /**
     * @var string
     */
    private $pay_id;

    /**
     * @param PayJpApi $api
     * @param string   $token_id
     */
    public function __construct($api, $token_id)
    {
        parent::__construct($api);
        $this->pay_id = $token_id;
    }

    /**
     * @param string $api_key
     * @param string $pay_id
     * @return CreatePay
     */
    public static function forge($api_key, $pay_id)
    {
        return new self(new PayJpApi($api_key), null, $pay_id);
    }

    /**
     * 支払いIDに関する情報を取得。
     *
     * @return bool
     */
    private function retrieve()
    {
        $charge = $this->payJp->retrieveCharge($this->pay_id);
        if (isset($return['error'])) {
            return $this->setError($return['error']);
        }
        $this->charge = $charge;
        return true;
    }

    /**
     * 与信済みの支払いIDについて、支払い処理を確定させる。
     *
     * @param int|null $amount
     */
    public function capture($amount = null)
    {
        if (!$this->retrieve()) {
            return;
        }
        if ($this->charge['captured']) {
            throw new \InvalidArgumentException('cannot capture the already captured charge.');
        }
        $parameter = $this->getRetrieveParameter($amount);
        $this->charge->capture($parameter);
    }

    /**
     * 支払いIDにたいして、キャンセル（全額返金）する。
     */
    public function cancel()
    {
        if (!$this->retrieve()) {
            return;
        }
        if ($this->charge['amount_refunded']) {
            throw new \InvalidArgumentException('cannot cancel the refunded charge.');
        }
        $this->charge->refund();
    }

    /**
     * 支払いIDにたいして、返金する。
     *
     * @param int $amount
     */
    public function refund($amount)
    {
        $amount = (int) $amount;
        if ($amount < 0) {
            throw new \InvalidArgumentException('cannot refund negative amount.');
        }
        if (!$this->retrieve()) {
            return;
        }
        if ($this->charge['amount_refunded']) {
            throw new \InvalidArgumentException('cannot refund the refunded charge.');
        }
        if ($this->charge['amount'] < $amount) {
            throw new \InvalidArgumentException('cannot refund more than the charged amount.');
        }
        $parameter = $this->getRetrieveParameter($amount);
        $this->charge->refund($parameter);
    }

    /**
     * @param int $amount
     * @return array|null
     */
    private function getRetrieveParameter($amount)
    {
        if (is_null($amount)) {
            return null;
        }
        return [
            'amount'   => $amount,
            'currency' => $this->payJp->getCurrency(),
        ];
    }
}