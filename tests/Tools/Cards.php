<?php
namespace tests\Tools;

class Cards
{
    private $cards;

    public function __construct()
    {
        $file       = dirname(__DIR__) . '/cards.ini';
        $this->cards = parse_ini_file($file, true);
    }

    /**
     * return a test card info for successful transaction. 
     * 
     * @return array
     */
    public function getSuccess()
    {
        return $this->cards['success'];
    }
}