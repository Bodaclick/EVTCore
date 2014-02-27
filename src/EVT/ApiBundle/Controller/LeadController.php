<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomain\User\PersonalInformation;
use EVT\CoreDomain\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
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
            throw new BadRequestHttpException('user not found');
        }

        try {
            $user = $evtUserFactory->createUserFromArray($leadData['user']);
            $lead = $evtLeadFactory->createLead($user, $leadData);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        try {
            $evtUserRepo->save($user);
        } catch (\Exception $e) {
            // User already exists
        }
        return array('lead' => $this->generateUrl('post_lead').'/'.$lead->getId());
    }

    public function getLeadsAction(Request $request)
    {
        $leadRepository = $this->container->get('evt.repository.lead');
        $leads =  $leadRepository->findByOwner('ownerusername');

        $leadsResponse = $this->render('EVTApiBundle:Lead:leads.html.twig', ['leads' => $leads]);

        return new Response($leadsResponse->getContent(), 200, array('Content-Type' => 'application/json'));
    }

    /**
     * Get one lead
     *
     * @View(statusCode=200)
     */
    public function getLeadAction($id)
    {
        $leadRepository = $this->container->get('evt.repository.lead');
        try {
            $lead = $leadRepository->findBy(array("id" => $id));
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        $leadsResponse = $this->render('EVTApiBundle:Lead:leads.html.twig', ['leads' => $lead]);
        return new Response($leadsResponse->getContent(), 200, array('Content-Type' => 'application/json'));
    }
}
