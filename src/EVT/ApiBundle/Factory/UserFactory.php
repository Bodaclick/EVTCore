<?php

namespace EVT\ApiBundle\Factory;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;

class UserFactory
{

    public function createUserFromArray($userArray)
    {
        // Validate the array throw InvalidArgumentException if any error
        $this->validateUser($userArray);

        $personalInfo = new PersonalInformation(
            $userArray['name'],
            $userArray['surname'],
            $userArray['phone']
        );

        $user = new User($userArray['email'], $personalInfo);

        return $user;
    }

    private function validateUser($array)
    {
        $arrayValidator = new ArrayValidator(['name', 'surname', 'email', 'phone']);
        $arrayValidator->validate($array);
    }
}
