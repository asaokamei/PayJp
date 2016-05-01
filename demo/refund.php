<?php

require_once __DIR__ . '/demo.inc.php';

$service = new PayService();
$factory = $service->getFactory();

$isOK    = true;
$message = '';
if ($id = getIdInPost()) {
    $charge  = $factory->retrieve($_POST['id']);
    if ($refund = getAmountInPost('refund')) {
        $charge->refund($refund);
        $message = 'refunded';
    } else {
        $charge->cancel();
        $message = 'canceled';
    }
} else {
    $isOK    = false;
    $message = 'not id or refund amount found.';
}
?>
<h1><a href="index.php" >top</a> &gt; Refund</h1>
<?php
echo showMessage($message, $isOK);
?>
<a href="list-pay.php">back to list</a>
