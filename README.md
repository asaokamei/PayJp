PayJp
=====

a simple wrapper classes for [Pay.JP](https://pay.jp/) and [WebPay](https://webpay.jp) payment services. 

!!!NOT FULLY TESTED nor USED!!!

### License

MIT License

### PSR

PSR-1, PSR-2, and PSR-4.

### install

```sh
composer require "asaokamei/payjp"
```

to see a demo, try

```sh
cd payjp
composer install
cd demo
php -S localhost:8888
```

and browse `http://localhost:8888/`. 

Getting Started
-----

Get public and secret api keys from Pay.jp or WebPay.jp.

```php
define('PAY_JP_SECRET_KEY', 'sk_test_*****'); // your secret key.
```

Then, create a factory for charges, as 

```php
// for Pay.jp
$factory = AsaoKamei\PayJp\PayJp\ChargeFactory::forge(PAY_JP_SECRET_KEY); 

// for WebPay.jp
$factory = AsaoKamei\PayJp\WebPay\ChargeFactory::forge(PAY_JP_SECRET_KEY); 
```

### charge to a credit card

get a credit token ([`$_POST['payjp-token']` for pay.jp](https://pay.jp/docs/cardtoken) or 
[`$_POST['webpay-token']` for WebPay.jp](https://webpay.jp/docs/payments_with_token)). 

```php
$charge  = $factory->create($_POST['service-token']);

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

```php
$charge = $factory->retrieve($charge_id);
```

then, do one of the following. 

```php
$charge->capture();
$charge->cancel();
$charge->refund(500);
```

You can only refund once for `pay.jp`. 


Testing
-------

To run the test, please obtain api keys for test from `Pay.jp` and `WebPay`. 

Set the secret and public api keys to `tests/services.ini` accordingly. 

Then, run phpunit using `tests/phpunit.xml` configuration. 


Notice
------

* `refund` is not working for pay.jp.
