<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomainBundle\Form\Type\GenericUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as FOS;

class ManagerController extends Controller
{
    /**
     * Create a new manager
     *
     * @FOS\View(statusCode=201)
     */
    public function postManagerAction(Request $request)
    {

        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->addRole('ROLE_MANAGER');

        $form = $this->createForm(new GenericUserFormType());
        $form->setData($user);
        $form->handleRequest($request);

        if($form->isValid()) {
            $userManager->updateUser($user);
            return sprintf('/api/managers/%d?apikey=%s', $user->getId(), $request->query->get('apikey'));
        }
        return $form;
    }
}
