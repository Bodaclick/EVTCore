<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\View;

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

        // TODO Get the user or create a new one
        $data = $evtLeadFactory->createLead(
            new User('valid@email.com', new PersonalInformation()),
            $request->request->get('lead')
        );

        //TODO return url lead
        return $data;
    }
}
