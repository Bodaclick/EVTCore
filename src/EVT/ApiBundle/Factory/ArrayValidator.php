<?php

namespace EVT\ApiBundle\Factory;

class ArrayValidator
{
    private $mustExist;

    public function __construct($mustExist)
    {
        $this->mustExist = $mustExist;
    }

    public function validate($array)
    {
        foreach ($this->mustExist as $element) {
            if (!isset($array[$element])) {
                throw new \InvalidArgumentException($element. ' not found');
            }
        }
    }
}
