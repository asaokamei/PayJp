<?php

require_once __DIR__ . '/demo.inc.php';

$service = new PayService();

if (isset($_POST) && $amount = getAmountInPost()) {
    createPayment($service, $_POST, $amount);
}

?>
<h1><a href="index.php" >top</a> &gt; Create New Payment</h1>

<?php if ($service->service === PayService::PAY_JP): ?>

    <form action="" method="post">
        <input type="text" name="amount" value="2345" />
        <input type="checkbox" name="authorize" value="auth" />
        <script src="https://checkout.pay.jp/" class="payjp-button" data-key="<?= $service->pub; ?>"></script>
    </form>
    
<?php elseif ($service->service === PayService::WEB_PAY): ?>

<?php endif; ?>

<?php

/**
 * @param PayService $service
 * @param array      $post
 * @param int        $amount
 */
function createPayment(PayService $service, $post, $amount)
{
    if ($service->service === PayService::PAY_JP) {
        createPayJp($service, $post, $amount);
    }
}

/**
 * @param PayService $service
 * @param array      $post
 * @param int        $amount
 */
function createPayJp(PayService $service, $post, $amount)
{
    if (!$card_id = $service->getCardIdInPost($post)) {
        return;
    }
    $factory = $service->getFactory();
    if (isset($post['authorize']) && $post['authorize'] === 'auth') {
        $factory->create($card_id)->authorize($amount);
    } else {
        $factory->create($card_id)->charge($amount);
    }
}