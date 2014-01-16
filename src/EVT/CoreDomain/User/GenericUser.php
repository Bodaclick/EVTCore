<?php

namespace EVT\CoreDomain\User;

use EVT\CoreDomain\Email;
use EVT\CoreDomain\User\PersonalInformation;

/**
 * GenericUser
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
abstract class GenericUser
{
    protected $personalInfo;
    protected $email;

    /**
     * Create a new GenericUser
     *
     * @param string $name  The name of the User
     * @param string $email The email of the User
     *
     * @throws \InvalidArgumentException If email not valid
     */
    public function __construct($email, PersonalInformation $personalInfo)
    {
        $this->email = new Email($email);
        $this->personalInfo = $personalInfo;
    }

    public function getPersonalInformation()
    {
        return $this->personalInfo;
    }

    public function getEmail()
    {
        return $this->email->getEmail();
    }
}
