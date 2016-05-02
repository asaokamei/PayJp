<?php
/**
 * Created by PhpStorm.
 * User: asao
 * Date: 2016/05/01
 * Time: 13:29
 */
namespace AsaoKamei\PayJp\Interfaces;

use AsaoKamei\PayJp\PayJp\CreatePay;

interface CreatePayInterface
{
    /**
     * 与信（オーソリ）を行う場合の認証の期間を日数で指定する。
     * 有効な日数は１日〜６０日まで。
     *
     * @param int $days
     * @return $this
     */
    public function setExpireDays($days);

    /**
     * カードトークンから指定した金額を引き落とす。
     * 成功したら支払いIDを返す。
     *
     * @param int $amount
     * @return bool|string
     */
    public function charge($amount);

    /**
     * カードトークンから指定した金額を与信（オーソリ）する。
     * 成功したら支払いIDを返す。
     *
     * @param int $amount
     * @return bool|string
     */
    public function authorize($amount);
}