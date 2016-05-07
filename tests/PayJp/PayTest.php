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
        foreach($factories as $service => $f) {
            $list = $f->getList();
            $this->assertTrue(count($list)>0);
        }
    }
}