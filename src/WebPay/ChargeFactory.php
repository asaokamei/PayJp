<?php
namespace AsaoKamei\PayJp\WebPay;

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use AsaoKamei\PayJp\Interfaces\CreatePayInterface;
use AsaoKamei\PayJp\Interfaces\UpdatePayInterface;
use WebPay\Charge;
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
        return new self(new WebPay($api_key), $currency);
    }

    /**
     * @return Charge
     */
    private function getCharge()
    {
        return $this->web_pay->charge;
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
     * @return UpdatePayInterface
     */
    public function retrieve($charge_id)
    {
        $this->getCharge()->retrieve($charge_id);
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return UpdatePayInterface[]
     */
    public function getList($limit = 10, $offset = 0)
    {
        $list = $this->getCharge()->all([
            'limit'  => $limit,
            'offset' => $offset,
        ]);
        $found = [];
        $count = $list->count;
        $data  = $list->data;
        for($i = 0; $i < $count; $i++) {
            $found[] = new UpdateCharge($this->web_pay, null, $data[$i]);
        }
        return $found;
    }
}