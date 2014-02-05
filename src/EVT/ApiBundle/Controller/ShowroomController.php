<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;

class ShowroomController extends Controller
{
    /**
     * @View(statusCode=201)
     */
    public function postShowroomsAction(Request $request)
    {
        $evtShowroomFactory = $this->container->get('evt.factory.showroom');

        $vertical = $request->request->get('vertical');
        $provider = $request->request->get('provider');
        $score = $request->request->get('score');

        $showroom = $evtShowroomFactory->createShowroom($vertical, $provider, $score);

        return ['showroom' => '/api/showrooms/' .$showroom->getId()];
    }
}
