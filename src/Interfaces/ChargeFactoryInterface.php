<?php
/**
 * Created by PhpStorm.
 * User: asao
 * Date: 2016/05/01
 * Time: 13:25
 */
namespace AsaoKamei\PayJp\Interfaces;

interface ChargeFactoryInterface
{
    /**
     * @param string $card_id
     * @return CreatePayInterface
     */
    public function create($card_id);

    /**
     * @param $charge_id
     * @return UpdatePayInterface
     */
    public function retrieve($charge_id);

    /**
     * @param int $limit
     * @param int $offset
     * @return UpdatePayInterface[]
     */
    public function getList($limit = 10, $offset = 0);
}