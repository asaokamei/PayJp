<?php
namespace tests\PayJp;

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
        $this->markTestIncomplete('not ready yet.');
    }

    function test0()
    {
        $this->assertTrue(true);
    }
}