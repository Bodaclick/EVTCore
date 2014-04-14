<?php

namespace EVT\CoreDomain\Provider;

use EVT\CoreDomain\Lead\Location;
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
    private $location;
    private $lang;

    public function __construct(
        ProviderId $id,
        $name,
        EmailCollection $notificationEmails,
        $lang,
        Location $location = null
    ) {
        $this->id = $id->getValue();
        $this->name = $name;
        $this->slugify();
        $this->notificationEmails = $notificationEmails;
        $this->managers = new \ArrayObject();
        $this->location = $location;
        $this->lang = $lang;
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

    public function getNotificationsEmailsAsString()
    {
        $arrayEmails = [];
        $notificationIterator = $this->notificationEmails->getIterator();
        foreach ($notificationIterator as $email) {
            array_push($arrayEmails, $email->getEmail());
        }

        return implode(', ', $arrayEmails);
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

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getLocation()
    {
        return $this->location;
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
