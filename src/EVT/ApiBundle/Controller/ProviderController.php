<?php

namespace EVT\ApiBundle\Controller;

use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use EVT\CoreDomainBundle\Form\Type\ProviderFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use FOS\RestBundle\Controller\Annotations\View;

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
        $form = $this->createForm($this->get('evt.form.provider'));

        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            throw new ConflictHttpException('Provider already exists');
        }

        if ($form->isValid()) {
            $providerRepo = $this->get('evt.repository.provider');
            $provider = $form->getData();
            $providerRepo->save($provider);

            return ['provider' => '/api/providers/' .$provider->getId()];
        }

        return $form;
    }
}
