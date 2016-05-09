<?php
namespace tests\PayJp;

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use PHPUnit_Framework_TestCase;
use tests\Tools\Cards;
use tests\Tools\Services;

class RetrieveTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Services
     */
    private $init;

    /**
     * @var Cards
     */
    private $cards;

    function setup()
    {
        $this->init  = new Services();
        $this->cards = new Cards();
    }

    /**
     * @test
     */
    function refund_and_cancel_charges()
    {
        $factories = $this->init->getFactories();
        foreach ($factories as $service => $f) {
            $this->refundAndCancel($f);
        }
    }

    /**
     * @param ChargeFactoryInterface $factory
     */
    private function refundAndCancel($factory)
    {
        // creating a new charge.
        $charge    = $factory->create($this->cards->getSuccess());
        $amount    = rand(2000, 9999);
        $charge_id = $charge->charge($amount);
        $this->assertTrue(strlen($charge_id) > 0);

        // now retrieve and refund it.
        $list = $factory->getList(1);
        $this->assertEquals(1, count($list));
        $retrieved = $list[0];
        
        $this->assertEquals($charge_id, $retrieved->getId());
        $this->assertEquals($amount, $retrieved->getAmount());
        $this->assertTrue($retrieved->getAmount() > 1000);
        
        // now refund 1000 yen.
        $retrieved->refund(1000);

        // retrieve again.
        $retrieved = $factory->getList(1)[0];
        $this->assertEquals($amount, $retrieved->getAmount());
        $this->assertEquals(1000, $retrieved->getAmountRefund());
        $this->assertTrue($retrieved->isRefund());
        
        // now cancel the charge.
        $retrieved->cancel();

        // retrieve again.
        $retrieved = $factory->getList(1)[0];
        $this->assertEquals($amount, $retrieved->getAmount());
        $this->assertEquals($amount, $retrieved->getAmountRefund());
        $this->assertTrue($retrieved->isRefund());
    }

}