<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FosView;
use FOS\RestBundle\Util\Codes;
use EVT\CoreDomainBundle\Form\Type\ProviderFormType;

/**
 * ProviderController
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderController extends Controller
{
    /**
     * The lead creation form
     *
     * @View()
     */
    public function newProviderAction(Request $request)
    {
        return ['apikey' => $request->query->get('apikey')];
    }

    /**
     * Create a new provider
     *
     * @View(statusCode=201)
     */
    public function postProviderAction(Request $request)
    {
        $factory = $this->get('evt.factory.provider');

        $providerData = $request->request->get('provider');

        try {
            $provider = $factory->createProvider($providerData);
        } catch (\InvalidArgumentException $e) {
            $view = new FosView();
            $view->setResponse(new Response($e->getMessage()));
            $view->setStatusCode(Codes::HTTP_BAD_REQUEST);
            return $view;
        }
        return ['provider' => '/api/providers/' .$provider->getId()];
    }
}
