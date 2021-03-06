<?php

use AsaoKamei\PayJp\Interfaces\UpdatePayInterface;

require_once __DIR__ . '/demo.inc.php';

$service = new PayService();
$factory = $service->getFactory();
$list = $factory->getList();

?>
<h1><a href="index.php" >top</a> &gt; Payment List</h1>
<?php 
foreach($list as $charge) {
    echo '<ul>';
    echo chargeInfo($charge);
    echo '</ul>';
}

function chargeInfo(UpdatePayInterface $charge)
{
    $html = "
    <li>{$charge->getId()}</li>
    <li>created at: {$charge->getCreatedAt()->format('Y/m/d H:i')}</li>
    <li>amount: {$charge->getAmount()}</li>
    ";

    if ($charge->isRefund()) {
        $html .= "<li>refund amount: {$charge->getAmountRefund()}</li>";
    }

    if (!$charge->isCaptured()) {
        $html .= "<li>capture: <form method='post' action='capture.php' style='display: inline;'>
            <input type='hidden' name='id' value='{$charge->getId()}'/>
            <input type='submit' value='capture this charge'/>
        </form></li>
        ";
    } elseif ($charge->getAmount() > $charge->getAmountRefund()) {
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