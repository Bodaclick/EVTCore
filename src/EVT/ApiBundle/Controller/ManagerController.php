<?php

namespace EVT\ApiBundle\Controller;

use Doctrine\DBAL\DBALException;
use EVT\CoreDomainBundle\Form\Type\GenericUserFormType;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;

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
     * @FOS\View()
     */
    public function postManagerAction(Request $request)
    {
        $view = new View(null, Codes::HTTP_CREATED);

        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setEnabled(true);
        $user->addRole('ROLE_MANAGER');

        $form = $this->createForm(new GenericUserFormType());
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager->updateUser($user);

            return $view->setData(['manager' => sprintf('/api/managers/%d', $user->getId())]);
        }

        if ($user = $this->container->get('evt.repository.user')->findOneByEmail($user->getEmail())) {
            $view->setStatusCode(Codes::HTTP_CONFLICT);
            return $view->setData(['manager' => sprintf('/api/managers/%d', $user->getId())]);
        }

        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate('EVTApiBundle:Manager:newManager.html.twig');
        $view->setData(['form' => $form->createView()]);
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
