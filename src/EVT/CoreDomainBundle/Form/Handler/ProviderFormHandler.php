<?php

namespace EVT\CoreDomainBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * ProviderFormHandler
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class ProviderFormHandler
{
    protected $form;
    protected $request;
    protected $em;

    public function __construct(FormInterface $form, Request $request, EntityManager $em)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
    }

    public function process()
    {
        $this->form->bind($this->request);

        if ($this->form->isValid()) {
            $this->em->save($this->form->getData());

            return true;
        }

        return false;
    }
}
