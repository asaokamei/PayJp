PayJp
=====

a simple wrapper classes for [Pay.JP](https://pay.jp/) payment service. 

!!!NOT REALLY TESTED!!!

### License

MIT License

### PSR

PSR-1, PSR-2, and PSR-4.

### install

```sh
composer require "asaokamei/payjp"
```

Getting Started
-----

Get public and secret api keys from Pay.JP

```php
define('PAY_JP_SECRET_KEY', 'sk_test_*****'); // your secret key.
define('PAY_JP_PUBLIC_KEY', 'pk_test_*****'); // your public key.
```

### charge to a credit card

get a credit token using [checkout](https://pay.jp/docs/cardtoken). 
The token is passed to a PHP script as `$_POST['payjp-token']`. 

```php
$charge = CreatePay::forge(PAY_JP_SECRET_KEY, $_POST['payjp-token']);
if (!$charge_id = $charge->charge(1000)) {
    var_dump($charge->getError());
}
```

You can authorize the credit, by,

```php
$charge_id = $charge->authorize(1000);
```

and save the `$charge_id` for later use. 


### capture, cancel, or refund

never tested!

```php
$charge = UpdatePay::forge(PAY_JP_SECRET_KEY, $charge_id);
```

then, do one of the following. 

```php
$charge->capture();
$charge->cancel();
$charge->refund(500);
```

You can only cancel or refund once once. 