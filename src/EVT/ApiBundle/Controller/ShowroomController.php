<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FosView;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @View(statusCode=200)
     */
    public function getShowroomsAction(Request $request)
    {
        $view = FosView::create();
        $view->setFormat('json');

        $showroomRepository = $this->container->get('evt.repository.showroom');
        $showrooms =  $showroomRepository->findByOwner($request->query);

        $statusCode = Codes::HTTP_OK;
        if (empty($showrooms)) {
            $statusCode = Codes::HTTP_NOT_FOUND;
            return new Response('', $statusCode);
        }

        return $view->setStatusCode($statusCode)->setData($showrooms);
    }

    /**
     * @View(statusCode=200)
     */
    public function getShowroomAction(Request $request, $id)
    {
        $view = FosView::create();
        $view->setFormat('json');

        $showroomRepository = $this->container->get('evt.repository.showroom');
        $showrooms =  $showroomRepository->findByIdOwner($id, $request->get('canView', null), $request->get('page', 1));

        $statusCode = Codes::HTTP_OK;
        if (empty($showrooms)) {
            $statusCode = Codes::HTTP_NOT_FOUND;
            return new Response('', $statusCode);
        }

        return $view->setStatusCode($statusCode)->setData($showrooms);
    }
}
