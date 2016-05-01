<?php
namespace AsaoKamei\PayJp\PayJp;

use AsaoKamei\PayJp\Interfaces\UpdatePayInterface;
use DateTime;
use InvalidArgumentException;
use Payjp\Charge;

class UpdatePay extends AbstractPayJp implements UpdatePayInterface
{
    /**
     * @var Charge
     */
    public  $charge;

    /**
     * @var string
     */
    private $charge_id;

    /**
     * @param PayJpApi $api
     * @param string   $charge_id
     * @param Charge   $charge
     */
    public function __construct($api, $charge_id = null, $charge = null)
    {
        parent::__construct($api);
        if (!is_null($charge_id)) {
            $this->charge_id = $charge_id;
            $this->retrieve();
        } elseif (!empty($charge)) {
            $this->charge    = $charge;
            $this->charge_id = $charge['id'];
        } else {
            throw new InvalidArgumentException;
        }
    }

    /**
     * 支払いIDに関する情報を取得。
     *
     * @return bool
     */
    private function retrieve()
    {
        $charge = $this->payJp->retrieveCharge($this->charge_id);
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
        if (!$this->isOK()) {
            return;
        }
        if ($this->charge['captured']) {
            throw new InvalidArgumentException('cannot capture the already captured charge.');
        }
        $parameter = $this->getRetrieveParameter($amount);
        $this->charge->capture($parameter);
    }

    /**
     * 支払いIDにたいして、キャンセル（全額返金）する。
     * 
     * 部分返金を行った場合は、キャンセルのみ可能。
     */
    public function cancel()
    {
        if (!$this->isOK()) {
            return;
        }
        $this->charge->refund();
    }

    /**
     * 支払いIDにたいして、返金する。
     * 
     * 部分返金を行った場合は、再度の返金は不可。ただしキャンセルは可能。
     *
     * @param int $amount
     */
    public function refund($amount)
    {
        $amount = (int) $amount;
        if (!$this->isOK()) {
            return;
        }
        if ($amount < 0) {
            throw new InvalidArgumentException('cannot refund negative amount.');
        }
        if ($this->charge['amount_refunded']) {
            throw new InvalidArgumentException('cannot refund the refunded charge.');
        }
        if ($this->charge['amount'] < $amount) {
            throw new InvalidArgumentException('cannot refund more than the charged amount.');
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

    /**
     * @return string
     */
    public function getId()
    {
        return $this->charge_id;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return (int) $this->charge['amount'];
    }

    /**
     * @return int
     */
    public function getAmountRefund()
    {
        return (int) $this->charge['amount_refunded'];
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return new DateTime(date('Y-m-d H:i:s', $this->charge['created']));
    }
    
    /**
     * @return bool
     */
    public function isCaptured()
    {
        return $this->charge['captured'];
    }

    /**
     * @return bool
     */
    public function isRefund()
    {
        return $this->charge['refunded'];
    }
}