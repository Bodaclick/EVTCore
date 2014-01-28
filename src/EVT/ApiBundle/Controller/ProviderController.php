<?php

namespace EVT\ApiBundle\Controller;

use EVT\CoreDomainBundle\Form\Type\ProviderFormType;
use EVT\CoreDomainBundle\Form\Handler\ProviderFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\View\View as FOSView;
use FOS\RestBundle\Util\Codes;

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
        $form = $this->createForm(new ProviderFormType());
        return ['form' => $form->createView(), 'apikey' => $request->query->get('apikey')];
    }

    /**
     * Create a new provider
     *
     * @View(statusCode=201)
     */
    public function postProviderAction(Request $request)
    {
        $form = $this->createForm(new ProviderFormType());
        $formHandler = new ProviderFormHandler($form, $request, $this->getDoctrine()->getManager());

        if ($formHandler->process()) {
            return ['provider' => $form->getData()->getId()];
        }

        return '{"ko"}';
    }
}
