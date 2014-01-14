<?php
namespace EVT\CoreDomain;

abstract class GenericId
{
    protected $value;
    
    public function __construct($value)
    {
        $this->value = (string) $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}
