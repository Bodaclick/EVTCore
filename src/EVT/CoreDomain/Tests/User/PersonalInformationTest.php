<?php

namespace EVT\CoreDomain\Tests\User;

use EVT\CoreDomain\User\PersonalInformation;

class PersonalInformationTest extends \PHPUnit_Framework_TestCase
{
    public function testPersonalInfo()
    {
        $personalInfo = new PersonalInformation('name', 'surnames', 'phone');
        $this->assertInstanceOf('\IteratorAggregate', $personalInfo);
        $this->assertClassHasAttribute('name', 'EVT\CoreDomain\User\PersonalInformation');
        $this->assertClassHasAttribute('surnames', 'EVT\CoreDomain\User\PersonalInformation');
        $this->assertClassHasAttribute('phone', 'EVT\CoreDomain\User\PersonalInformation');
        $this->assertEquals('name', $personalInfo->name);
        $this->assertEquals('surnames', $personalInfo->surnames);
        $this->assertEquals('phone', $personalInfo->phone);
    }
}
