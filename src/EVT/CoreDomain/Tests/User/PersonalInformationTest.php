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
        $this->assertEquals('name', $personalInfo->getName());
        $this->assertEquals('surnames', $personalInfo->getSurnames());
        $this->assertEquals('phone', $personalInfo->getPhone());
    }

    /**
     *  @expectedException PHPUnit_Framework_Error_Warning
     */
    public function testNotOptionalValues()
    {
        new PersonalInformation();
    }

    public function emptiesProvider()
    {
        return [[''], [null]];
    }

    /**
     *  @expectedException InvalidArgumentException
     *  @dataProvider emptiesProvider
     */
    public function testNameNotEmpty($value)
    {
        new PersonalInformation($value, 'a', 'b');
    }

    /**
     *  @expectedException InvalidArgumentException
     *  @dataProvider emptiesProvider
     */
    public function testSurnameNotEmpty($value)
    {
        new PersonalInformation('c', $value, 'b');
    }

    /**
     *  @expectedException InvalidArgumentException
     *  @dataProvider emptiesProvider
     */
    public function testPhoneNotEmpty($value)
    {
        new PersonalInformation('c', 'a', $value);
    }
}
