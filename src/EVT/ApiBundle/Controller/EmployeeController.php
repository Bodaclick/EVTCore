<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomainBundle\Events\Event;
use EVT\CoreDomainBundle\Events\GenericUserEvent;
use EVT\CoreDomainBundle\Form\Type\GenericUserFormType;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;

/**
 * Class EmployeeController
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class EmployeeController extends Controller
{
    /**
     * @FOS\View()
     */
    public function newEmployeeAction()
    {
        return [];
    }

    /**
     * @FOS\View()
     */
    public function postEmployeeAction(Request $request)
    {
        $view = new View(null, Codes::HTTP_CREATED);

        $userEmployee = $this->container->get('fos_user.user_manager');

        $user = $userEmployee->createUser();
        $user->setEnabled(true);
        $user->addRole('ROLE_EMPLOYEE');

        $form = $this->createForm(new GenericUserFormType());
        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userEmployee->updateUser($user);

            $event = new GenericUserEvent($user, Event::ON_CREATE_EMPLOYEE);
            $asyncDispatcher = $this->container->get('bdk.async_event_dispatcher');
            $asyncDispatcher->dispatch($event);

            return $view->setData(['user' => sprintf('/api/employee/%d', $user->getId())]);
        }

        if ($userFindIt = $this->container->get('evt.repository.user')->findOneByEmail($user->getEmail())) {
            if (count($userFindIt->getRoles()) == 1 && $userFindIt->hasRole("ROLE_USER")) {
                $userFindIt->addRole('ROLE_EMPLOYEE');
                $userEmployee->updateUser($userFindIt);
                return $view->setData(['user' => sprintf('/api/employee/%d', $userFindIt->getId())]);
            } else {
                $view->setStatusCode(Codes::HTTP_CONFLICT);
                return $view->setData(['user' => sprintf('/api/employee/%d', $userFindIt->getId())]);
            }
        }

        $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
        $view->setTemplate('EVTApiBundle:Employee:newEmployee.html.twig');
        $view->setData(['form' => $form->createView()]);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * @FOS\View()
     */
    public function getEmployeesAction(Request $request)
    {
        $userRepository = $this->container->get('evt.repository.user');
        $users = $userRepository->getEmployees($request->get('canView', null), $request->get('page', 1));

        if (empty($users)) {
            $statusCode = Codes::HTTP_NOT_FOUND;
            return new Response('', $statusCode);
        }

        return $users;
    }
}
