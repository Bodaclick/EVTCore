<?php

namespace EVT\CoreDomain\Lead;

/**
 * EventType
 *
 * @author    Mario Cazorla  <mcazorla@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class Location
{
    private $lat;
    private $long;
    private $adminLevel1;
    private $adminLevel2;
    private $country;

    public function __construct($lat, $long, $adminLevel1, $adminLevel2, $country)
    {
        $this->lat = $lat;
        $this->long = $long;
        $this->adminLevel1 = $adminLevel1;
        $this->adminLevel2 = $adminLevel2;
        $this->country = $country;
    }

    public function getLatLong()
    {
        $latLong = array();
        $latLong['lat'] = $this->lat;
        $latLong['long'] = $this->long;

        return $latLong;
    }

    public function getAdminLevel1()
    {
        return $this->adminLevel1;
    }

    public function getAdminLevel2()
    {
        return $this->adminLevel2;
    }

    public function getCountry()
    {
        return $this->country;
    }
}
