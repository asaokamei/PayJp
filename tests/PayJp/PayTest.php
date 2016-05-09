<?php
namespace tests\PayJp;

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use AsaoKamei\PayJp\Interfaces\UpdatePayInterface;
use PHPUnit_Framework_TestCase;
use tests\Tools\Cards;
use tests\Tools\Services;

class PayTest extends PHPUnit_Framework_TestCase
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
        $this->markTestSkipped('skipped for writing retrieve tests.'); // todo delete this line when RetrieveTest is ready. 
        $this->init  = new Services();
        $this->cards = new Cards();
    }

    /**
     * @test
     */
    function create_a_new_charge_and_retrieve_it_from_the_service()
    {
        $factories = $this->init->getFactories();
        foreach ($factories as $service => $f) {
            $this->createAndRetrieve($f);
        }
    }

    /**
     * @param ChargeFactoryInterface $factory
     * @return UpdatePayInterface
     */
    private function createAndRetrieve($factory)
    {
        // creating a new charge.
        $charge    = $factory->create($this->cards->getSuccess());
        $amount    = rand(1000, 9999);
        $charge_id = $charge->charge($amount);
        $this->assertTrue(strlen($charge_id) > 0);

        // retrieving the created charge.
        $list = $factory->getList(1);
        $this->assertEquals($charge_id, $list[0]->getId());
        $this->assertEquals($amount, $list[0]->getAmount());

        return $list[0];
    }
}