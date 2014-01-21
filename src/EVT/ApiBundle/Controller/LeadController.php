<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FOSView;
use FOS\RestBundle\Util\Codes;

class LeadController extends Controller
{

    /**
     * The lead creation form
     *
     * @View()
     */
    public function newLeadAction()
    {

    }

    /**
     * Create a new lead
     *
     * @View(statusCode=201)
     */
    public function postLeadAction(Request $request)
    {
        $evtLeadFactory = $this->get('evt.lead.factory');
        $evtUserRepo = $this->get('evt.repository.user');

        // TODO Create the user
        $user = new User('valid@email.com', new PersonalInformation());

        try {

            $data = $evtLeadFactory->createLead($user, $request->request->get('lead'));

        } catch (\InvalidArgumentException $e) {

            $view = new FOSView($e->getMessage());
            $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
            return $view;

        }

        // TODO $evtUserRepo->save($user);

        //TODO return url lead
        return $data;
    }
}
