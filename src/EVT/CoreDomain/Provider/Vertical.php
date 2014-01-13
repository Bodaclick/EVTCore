<?php
namespace EVT\CoreDomain\Provider;

class Vertical
{
    private $domain;
    
    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function getDomain()
    {
        return $this->domain;
    }
}
