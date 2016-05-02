<?php
namespace AsaoKamei\PayJp\PayJp;

use ArrayAccess;
use Payjp\Charge;
use Payjp\Collection;
use Payjp\Error\Authentication;
use Payjp\Error\InvalidRequest;
use Payjp\Payjp;

class PayJpApi
{
    /**
     * @var string
     */
    private $currency;

    /**
     * @param string $api_key
     * @param string $currency
     */
    public function __construct($api_key, $currency = 'jpy')
    {
        Payjp::setApiKey($api_key);
        $this->currency = $currency;
    }

    /**
     * @param array $parameter
     * @return Charge|ArrayAccess
     */
    public function createCharge(array $parameter)
    {
        try {
            return Charge::create($parameter);

        } catch(Authentication $e) {
            return $this->returnError($e);
        } catch(InvalidRequest $e) {
            return $this->returnError($e);
        }
    }

    private function returnError(\Exception $e)
    {
        return ['error' => [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'type' => 'auth_error',
        ]];
    }

    /**
     * @param string $pay_id
     * @return Charge|ArrayAccess
     */
    public function retrieveCharge($pay_id)
    {
        return Charge::retrieve($pay_id);
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|Collection
     */
    public function getChargeList($limit = 10, $offset = 0)
    {
        return Charge::all(array("limit" => $limit, "offset" => $offset));
    }
}