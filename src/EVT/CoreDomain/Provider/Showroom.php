<?php
namespace EVT\CoreDomain\Provider;

class Showroom
{
    private $url;
    private $score;
    private $provider;
    private $vertical;
    
    public function __construct(Provider $provider, Vertical $vertical)
    {
        $this->provider = $provider;
        $this->vertical = $vertical;
    }
}
