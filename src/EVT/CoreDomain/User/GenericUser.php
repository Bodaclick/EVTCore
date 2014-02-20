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
    protected $id;
    protected $username;
    protected $email;
    protected $salt;
    protected $password;
    protected $personalInfo;
    protected $roles;

    /**
     * @param $email
     * @param PersonalInformation $personalInfo
     * @param null $salt
     * @param null $password
     * @param array $roles
     * @param null $username
     */
    public function __construct(
        $email,
        PersonalInformation $personalInfo,
        $salt = null,
        $password = null,
        $roles = [],
        $username = null
    )
    {
        $this->email = new Email($email);
        $this->personalInfo = $personalInfo;
        $this->salt = $salt;
        $this->password = $password;
        $this->roles = $roles;
        $this->username = $username;
    }

    public function getPersonalInformation()
    {
        return $this->personalInfo;
    }

    public function getEmail()
    {
        return $this->email->getEmail();
    }

    public function getId()
    {
        return $this->id;
    }
}
