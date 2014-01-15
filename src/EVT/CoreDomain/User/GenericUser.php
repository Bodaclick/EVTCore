<?php

namespace EVT\CoreDomain\User;

use EVT\CoreDomain\Email;

/**
 * GenericUser
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
abstract class GenericUser
{
    private $name;
    private $email;

    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = new Email($email);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email->getEmail();
    }
}
