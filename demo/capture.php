<?php

require_once __DIR__ . '/demo.inc.php';

$service = new PayService();
$factory = $service->getFactory();

$isOK    = true;
$message = '';
if ($id = getIdInPost()) {
    $charge  = $factory->retrieve($_POST['id']);
    if ($charge->isCaptured()) {
        $isOK    = false;
        $message = 'already captured';
    } else {
        $charge->capture();
        $message = 'captured';
    }
} else {
    $isOK    = false;
    $message = 'not id or refund amount found.';
}
?>
<h1><a href="index.php" >top</a> &gt; Capture</h1>
<?php
echo showMessage($message, $isOK);
?>
<a href="list-pay.php">back to list</a>
