<?php

namespace EVT\CoreDomain;

class EmailCollection extends \ArrayObject
{
    
    public function __construct(Email $email) {
        $this->append($email);
    }

    public function append(Email $value) {
        parent::append($value);
    }
}
