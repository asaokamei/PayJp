<?php

require_once __DIR__ . '/demo.inc.php';

$service = new PayService();
if ($service_name = getIdInPost('service')) {
    $key = getIdInPost('key');
    $service->setupService($service_name, $key);
}

?>
<h1>Credit Cards</h1>

<h3>Current Service</h3>

<?php if ($service->isLoaded()): ?>

    <?= $service->getInfo();?>
    <a href="new-pay.php">new payment</a><br/>
    <a href="list-pay.php">list payments</a>
    
<?php else: ?>
    
    <p>service not set. </p>

<?php endif; ?>

<h3>Pay.JP</h3>

<form method="post">

    <dl>
        <dt>pick service</dt>
        <dd>
            <label><input type="radio" name="service" value="<?= PayService::PAY_JP ?>" />use Pay.JP service</label><br/>
            <label><input type="radio" name="service" value="<?= PayService::WEB_PAY ?>" />use WebPay.JP service</label><br/>
        </dd>
        
        <dt>API-KEY</dt>
        <dd><input type="text" name="key" /></dd>

        <dt>&nbsp;</dt>
        <dd><input type="submit" value="set service and public api-key " /></dd>
        
    </dl>

</form>
