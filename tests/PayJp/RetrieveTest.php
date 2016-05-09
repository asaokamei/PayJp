<?php
namespace tests\PayJp;

use AsaoKamei\PayJp\Interfaces\ChargeFactoryInterface;
use PHPUnit_Framework_TestCase;
use tests\Tools\Services;

class RetrieveTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Services
     */
    private $init;

    function setup()
    {
        $this->init  = new Services();
    }

    /**
     * @test
     */
    function retrieve_with_limit_and_offset()
    {
        $factories = $this->init->getFactories();
        foreach ($factories as $service => $f) {
            $this->limitAndOffset($service, $f);
        }
    }

    /**
     * @param string                 $service
     * @param ChargeFactoryInterface $factory
     */
    private function limitAndOffset($service, $factory)
    {
        $all10 = $factory->getList(10, 0);
        if (count($all10) < 10) {
            $this->markTestIncomplete("need at least 10 charges to test {$service}...");
        }
        
        // retrieve the first 4 charges only.
        $first4 = $factory->getList(4, 0);
        $this->assertEquals(4, count($first4));
        foreach($first4 as $idx => $charge) {
            $this->assertEquals($all10[$idx]->getId(), $charge->getId()); 
        }
        // retrieve the next 4 charges.
        $first4 = $factory->getList(4, 4);
        $this->assertEquals(4, count($first4));
        foreach($first4 as $idx => $charge) {
            $this->assertEquals($all10[$idx+4]->getId(), $charge->getId());
        }
    }
}