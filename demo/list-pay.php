<?php

use AsaoKamei\PayJp\UpdatePay;

require_once __DIR__ . '/demo.inc.php';

$factory = getFactory();
$list = $factory->getList();

?>
<h1>List</h1>
<?php 
foreach($list as $charge) {
    echo '<ul>';
    echo chargeInfo($charge);
    echo '</ul>';
}

function chargeInfo(UpdatePay $charge)
{
    $html = "
    <li>{$charge->getId()}</li>
    <li>amount: {$charge->getAmount()}</li>
    ";
    
    if ($charge->isCaptured()) {
        $html .= "<li>captured at: {$charge->getCapturedAt()->format('Y/m/d H:i')}</li>";
    } else {
        $html .= "<li>capture: <form method='post' action='capture.php' style='display: inline;'>
            <input type='hidden' name='id' value='{$charge->getId()}'/>
            <input type='submit' value='capture this charge'/>
        </form></li>
        ";
    }
    if ($charge->isRefund()) {
        $html .= "<li>refund amount: {$charge->getAmountRefund()}</li>";
    } elseif ($charge->isCaptured()) {
        $html .= "<li>refund: <form method='post' action='refund.php' style='display: inline;'>
            <input type='hidden' name='id' value='{$charge->getId()}'/>
            <input type='text' name='refund' value='{$charge->getAmount()}'/>
            <input type='submit' value='refund'/>
        </form> OR 
        <form method='post' action='refund.php' style='display: inline;'>
            <input type='hidden' name='id' value='{$charge->getId()}'/>
            <input type='submit' value='cancel'/>
        </form></li>";
    }

    return $html;
}