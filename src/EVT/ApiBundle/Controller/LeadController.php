<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
        if (!isset($leadData['user'])) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('user not found');
        }

        try {
            $user = $evtUserFactory->createUserFromArray($leadData['user']);
            $lead = $evtLeadFactory->createLead($user, $leadData);
        } catch (\InvalidArgumentException $e) {
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($e->getMessage());
        }

        try {
            $evtUserRepo->save($user);
        } catch (\Exception $e) {
            // User already exists
        }
        return array('lead' => $this->generateUrl('post_lead').'/'.$lead->getId());
    }
}
