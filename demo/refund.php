<?php

require_once __DIR__ . '/demo.inc.php';

$isOK    = true;
$message = '';
if ($id = getIdInPost()) {
    $factory = getFactory();
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
<h1>Refund</h1>
<?php
echo showMessage($message, $isOK);
?>
<a href="list-pay.php">back to list</a>
