<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FOSView;

class ShowroomController extends Controller
{

    /**
     * Create a new lead
     *
     * @View(statusCode=201)
     */
    public function postShowroomsAction(Request $request)
    {
        $evtShowroomFactory = $this->container->get('evt.factory.showroom');

        $domain = $request->request->get('domain');
        $providerId = $request->request->get('providerId');
        $score = $request->request->get('score');

        $showroom = $evtShowroomFactory->createShowroom($domain, $providerId, $score);

        $ret['showroom'] = "/api/showrooms/" . $showroom->getId();

        return $ret;
    }
} 