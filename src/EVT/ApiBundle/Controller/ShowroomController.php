<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;

class ShowroomController extends Controller
{
    /**
     * The showroom creation form
     *
     * @View()
     */
    public function newShowroomAction(Request $request)
    {
        return array('apikey' => $request->query->get('apikey'));
    }

    /**
     * @View(statusCode=201)
     */
    public function postShowroomAction(Request $request)
    {
        $showroomFactory = $this->container->get('evt.factory.showroom');
        $data = $request->request->get('showroom');
        $extra_data = $request->request->get('extra_data');

        try {
            $showroom = $showroomFactory->createShowroom($data, $extra_data);
        } catch (\InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        return ['showroom' => '/api/showrooms/' .$showroom->getId()];
    }
}
