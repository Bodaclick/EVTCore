<?php

namespace EVT\CoreDomain;

use Egulias\EmailValidator\EmailValidator;

/**
 * Email
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class Email
{
    private $email;

    public function __construct($email)
    {
        $this->isValid($email);
        $this->email = $email;
    }

    private function isValid($email)
    {
        $validator = new EmailValidator;
        if (!$validator->isValid($email)) {
             throw new \InvalidArgumentException('Email is invalid');
        }
        return true;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
