<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;

class UserController extends Controller
{
    /**
     * @View(statusCode=200)
     */
    public function getUserAction($username)
    {
        $userRepo = $this->container->get('evt.repository.user');

        try {
            $user = $userRepo->getManagerByUsername($username);
            if (null === $user) {
                $user = $userRepo->getEmployeeByUsername($username);
            }
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return $user;
    }
}
