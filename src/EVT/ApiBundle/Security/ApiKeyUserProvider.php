<?php

namespace EVT\ApiBundle\Security;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/**
 * ApiKeyUserProvider
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    private $apikeys;

    public function __construct($apikeys)
    {
        $this->apikeys = $apikeys;
    }

    public function getUsernameForApiKey($apiKey)
    {
        if (isset($this->apikeys[$apiKey])) {
            return $this->apikeys[$apiKey]['name'];
        }
        return null;
    }

    public function loadUserByUsername($username)
    {
        return new User($username, null, array('ROLE_API'));
    }

    public function refreshUser(UserInterface $user)
    {
        // this is used for storing authentication in the session
        // but in this example, the token is sent in each request,
        // so authentication can be stateless. Throwing this exception
        // is proper to make things stateless
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return 'Symfony\Component\Security\Core\User\User' === $class;
    }
}
