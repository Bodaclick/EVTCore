<?php
namespace EVT\CoreDomain\Provider;

class Provider
{
    private $name;
    private $location;
    private $notificationMails;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLocation()
    {
        return $this->location;
    }
}
