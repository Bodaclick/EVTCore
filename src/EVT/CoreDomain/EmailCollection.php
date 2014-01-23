<?php

namespace EVT\CoreDomain;

/**
 * EmailCollection
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class EmailCollection extends \ArrayObject
{

    public function __construct(Email $email)
    {
        $this->append($email);
    }

    public function append($value)
    {
        if (!$value instanceof Email) {
            throw new \InvalidArgumentException();
        }
        parent::append($value);
    }
}
