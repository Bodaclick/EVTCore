<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;

class ResetPasswordController extends Controller
{
    /**
     *  @View(statusCode=200)
     */
    public function getResetPasswordAction($username)
    {
        $userRepo = $this->container->get('evt.repository.user');
        try {
            $newPassword = $userRepo->resetPassword($username);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        return ["passwd" => $newPassword];
    }
}
