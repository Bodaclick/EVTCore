<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FosView;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Response;

 /**
 * VerticalController
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 *
 * @copyright 2014 Bodaclick S.A.
 */

class VerticalController extends Controller
{
    /**
     * @View(statusCode=200)
     */
    public function getVerticalsAction()
    {
        $view = FosView::create();
        $view->setFormat('json');

        $verticalRepository = $this->container->get('evt.repository.vertical');
        $verticals = $verticalRepository->findAll();

        $statusCode = Codes::HTTP_OK;
        if (empty($verticals)) {
            $statusCode = Codes::HTTP_NOT_FOUND;
            return new Response('', $statusCode);
        }

        return $view->setStatusCode($statusCode)->setData($verticals);
    }
}
