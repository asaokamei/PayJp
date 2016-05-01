<?php
namespace AsaoKamei\PayJp\WebPay;

use AsaoKamei\PayJp\Interfaces\UpdatePayInterface;
use DateTime;
use WebPay\Charge;
use WebPay\WebPay;

class UpdateCharge implements UpdatePayInterface
{
    /**
     * @var WebPay
     */
    private $web_pay;

    /**
     * @var string
     */
    private $charge_id;

    /**
     * @var Charge
     */
    private $charge;

    public function __construct($web_pay, $charge_id = null, $charge = null)
    {
        $this->web_pay = $web_pay;
        if (!is_null($charge_id)) {
            $this->charge_id = $charge_id;
            $this->retrieve($charge_id);
        } elseif (!is_null($charge)) {
            $this->charge = $charge;
            $this->charge_id = $charge['id'];
        } else {
            throw new \InvalidArgumentException;
        }
    }

    /**
     * @param $charge_id
     */
    private function retrieve($charge_id)
    {
        /** @var Charge $charge */
        $charge = $this->web_pay->charge;
        $this->charge = $charge->retrieve(['id' =>$charge_id]);
    }

    /**
     * 与信済みの支払いIDについて、支払い処理を確定させる。
     *
     * @param int|null $amount
     */
    public function capture($amount = null)
    {
        $parameter = [
            'id' =>$this->charge_id
        ];
        if (!is_null($amount) && is_numeric($amount)) {
            $parameter['amount'] = $amount;
        }
        $this->charge->capture($parameter);
    }

    /**
     * 支払いIDにたいして、キャンセル（全額返金）する。
     *
     * 部分返金を行った場合は、キャンセルのみ可能。
     */
    public function cancel()
    {
        // TODO: Implement cancel() method.
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
        // TODO: Implement refund() method.
    }

    /**
     * @return string
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        // TODO: Implement getAmount() method.
    }

    /**
     * @return int
     */
    public function getAmountRefund()
    {
        // TODO: Implement getAmountRefund() method.
    }

    /**
     * @return DateTime
     */
    public function getCapturedAt()
    {
        // TODO: Implement getCapturedAt() method.
    }

    /**
     * @return bool
     */
    public function isCaptured()
    {
        // TODO: Implement isCaptured() method.
    }

    /**
     * @return bool
     */
    public function isRefund()
    {
        // TODO: Implement isRefund() method.
    }
}