<?php
namespace tests\PayJp;

use PHPUnit_Framework_TestCase;
use tests\Tools\Init;

class PayTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Init
     */
    private $init;

    function setup()
    {
        $this->init = new Init();
    }

    function test0()
    {
        $factories = $this->init->getFactories();
        foreach ($factories as $service => $f) {
            // creating a new charge.
            $charge    = $f->create([
                'number'    => '4242424242424242',
                'exp_month' => '12',
                'exp_year'  => '2020',
                'cvc'       => '123',
                'name'      => 'YUI ARAGAKI',
            ]);
            $amount    = rand(1000, 9999);
            $charge_id = $charge->charge($amount);
            $this->assertTrue(strlen($charge_id)>0);
            
            // retrieving the created charge.
            $list = $f->getList(1);
            $this->assertEquals($charge_id, $list[0]->getId());
            $this->assertEquals($amount, $list[0]->getAmount());
        }
    }

    function get_a_list_of_charges_from_test_account()
    {
        $factories = $this->init->getFactories();
        foreach ($factories as $service => $f) {
            $list = $f->getList();
            $this->assertTrue(count($list) > 0);
        }
    }
}