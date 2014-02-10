<?php

namespace EVT\ApiBundle\Factory;

class ShowroomWithExtraData
{
    private $showroom;
    private $extra_data;

    public function __construct($showroom, $extra_data)
    {
        $this->showroom = $showroom;
        $this->extra_data = $extra_data;
    }
}
