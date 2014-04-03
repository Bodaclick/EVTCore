<?php

namespace EVT\CoreDomainBundle\Test\Mapping;

use EVT\CoreDomain\Provider\Vertical as DomainVertical;
use EVT\CoreDomainBundle\Entity\Vertical;
use EVT\CoreDomainBundle\Mapping\VerticalMapping;

class VerticalMappingTest extends \PHPUnit_Framework_TestCase
{

    public function testDomainToEntityIsMapped()
    {
        $dVertical = new DomainVertical('domain', 'es_ES');

        $mapper = new VerticalMapping();
        $eVertical = $mapper->mapDomainToEntity($dVertical);

        $this->assertInstanceOf('EVT\CoreDomainBundle\Entity\Vertical', $eVertical);
        $this->assertEquals($dVertical->getDomain(), $eVertical->getDomain());
    }


    public function testEntityToDomainIsMapped()
    {
        $eVertical = new Vertical();
        $eVertical->setDomain('domain');

        $mapper = new VerticalMapping();
        $dVertical = $mapper->mapEntityToDomain($eVertical);

        $this->assertInstanceOf('EVT\CoreDomain\Provider\Vertical', $dVertical);
        $this->assertEquals($dVertical->getDomain(), $eVertical->getDomain());
    }
}
