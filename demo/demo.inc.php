<?php
use AsaoKamei\PayJp\PayJp\ChargeFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';
date_default_timezone_set('Asia/Tokyo');

/**
 * @return ChargeFactory
 */
function getFactory()
{
    return ChargeFactory::forge("sk_test_80c6bed519a1a374d29d3f6c");
}

/**
 * @return bool|string
 */
function getIdInPost()
{
    if (isset($_POST) && isset($_POST['id']) && preg_match('/^[_0-9a-zA-Z]+$/', $_POST['id'])) {
        return $_POST['id'];
    }

    return false;
}

/**
 * @param string $key
 * @return bool|string
 */
function getAmountInPost($key = 'amount')
{
    if (isset($_POST) && isset($_POST[$key]) && preg_match('/^[_0-9]+$/', $_POST[$key])) {
        return $_POST[$key];
    }

    return false;
}

/**
 * @param string $message
 * @param bool   $isOK
 * @return string
 */
function showMessage($message, $isOK = true)
{
    if (!$message) {
        return '';
    }
    if (!$isOK) {
        $message = "<span style='color: red'>{$message}</span>";
    }
    $message = "<bold>{$message}</bold><br/><br/>";
    return $message;
}
