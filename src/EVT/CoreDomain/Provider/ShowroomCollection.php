<?php
namespace EVT\CoreDomain\Provider;

class ShowroomCollection extends \ArrayObject
{
    public static function createShowroom(Provider $provider, Vertical $vertical)
    {
        $this->append(new Showroom($provider, $vertical));
    } 
}
