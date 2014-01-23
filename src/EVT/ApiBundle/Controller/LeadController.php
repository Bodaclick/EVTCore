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
     * @Template()
     */
    public function newLeadAction(Request $request)
    {
        return array('apikey' => $request->query->get('apikey'));
    }

    /**
     * Create a new lead
     *
     * @View(statusCode=201)
     */
    public function postLeadAction(Request $request)
    {
        $evtLeadFactory = $this->get('evt.factory.lead');
        $evtUserFactory = $this->get('evt.factory.user');
        $evtUserRepo = $this->get('evt.repository.user');

        $leadData = $request->request->get('lead');

        try {
            if (!isset($leadData['user'])) {
                throw new \InvalidArgumentException('user not found');
            }
            $user = $evtUserFactory->createUserFromArray($leadData['user']);
            $lead = $evtLeadFactory->createLead($user, $leadData);
        } catch (\InvalidArgumentException $e) {
            $view = new FOSView('Something went wrong, please try again later');
            $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
            return $view;

        }

        try {
            $evtUserRepo->save($user);
        } catch (\Exception $e) {
            // User already exists
        }

        return $this->generateUrl('post_lead').'/'.$lead->getId();
    }
}
