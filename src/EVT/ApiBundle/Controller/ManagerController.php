<?php

namespace EVT\ApiBundle\Controller;

use Doctrine\DBAL\DBALException;
use EVT\CoreDomainBundle\Form\Type\GenericUserFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Class ManagerController
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class ManagerController extends Controller
{
    /**
     * @FOS\View()
     */
    public function newManagerAction()
    {
        return [];
    }

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

        if ($form->isValid()) {
            try {
                $userManager->updateUser($user);
            } catch (DBALException $e) {
                throw new ConflictHttpException('User already exists');
            }
            return ['manager' => sprintf('/api/managers/%d', $user->getId())];
        }
        return $form;
    }
}
