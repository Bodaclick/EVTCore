<?php

namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\User\Manager;
use EVT\CoreDomain\EmailCollection;

/**
 * Provider
 *
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
class Provider
{
    private $id;
    private $name;
    private $slug;
    private $phone;
    private $notificationEmails;
    private $managers;

    public function __construct(ProviderId $id, $name, EmailCollection $notificationEmails)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slugify();
        $this->notificationEmails = $notificationEmails;
        $this->managers = new \ArrayObject();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getNotificationEmails()
    {
        return $this->notificationEmails;
    }

    public function addManager(Manager $manager)
    {
        if (!$this->managers->offsetExists($manager->getEmail())) {
            $this->managers->offsetSet($manager->getEmail(), $manager);
        }
    }

    public function getManagers()
    {
        return $this->managers;
    }

    private function slugify()
    {
        // Code from https://github.com/KnpLabs/DoctrineBehaviors
        $this->slug = strtolower(trim(preg_replace(
            "/[^a-zA-Z0-9\/_|+ -]/",
            '',
            iconv('UTF-8', 'ASCII//TRANSLIT', $this->name)
        ), '-'));
        $this->slug = preg_replace("/[\/_|+ -]+/", '-', $this->slug);
    }
}
