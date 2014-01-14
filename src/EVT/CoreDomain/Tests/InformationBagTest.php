<?php

namespace EVT\CoreDomain\Tests;

use EVT\CoreDomain\InformationBag;

class InformationBagTest extends \PHPUnit_Framework_TestCase
{
    public function testAddElements()
    {
        $informationBag = new InformationBag();
        $informationBag->set('key', 'value');
        $this->assertEquals('value', $informationBag->get('key'));
        $this->assertCount(1, $informationBag);
    }

    public function testConstruction()
    {
        $informationBag = new InformationBag(['key' => 'value']);
        $this->assertEquals('value', $informationBag->get('key'));
        $this->assertCount(1, $informationBag);
    }
}
