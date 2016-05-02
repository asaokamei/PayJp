<?php
/**
 * Created by PhpStorm.
 * User: asao
 * Date: 2016/05/01
 * Time: 13:51
 */
namespace AsaoKamei\PayJp\Interfaces;

use DateTime;

interface UpdatePayInterface
{
    /**
     * 与信済みの支払いIDについて、支払い処理を確定させる。
     *
     * @param int|null $amount
     */
    public function capture($amount = null);

    /**
     * 支払いIDにたいして、キャンセル（全額返金）する。
     *
     * 部分返金を行った場合は、キャンセルのみ可能。
     */
    public function cancel();

    /**
     * 支払いIDにたいして、返金する。
     *
     * 部分返金を行った場合は、再度の返金は不可。ただしキャンセルは可能。
     *
     * @param int $amount
     */
    public function refund($amount);

    /**
     * @return string
     */
    public function getId();

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @return int
     */
    public function getAmountRefund();

    /**
     * @return DateTime
     */
    public function getCreatedAt();

    /**
     * @return bool
     */
    public function isCaptured();

    /**
     * @return bool
     */
    public function isRefund();
}