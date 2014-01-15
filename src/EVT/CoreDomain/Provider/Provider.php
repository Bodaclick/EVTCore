<?php
namespace EVT\CoreDomain\Provider;

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
    private $notificationEmails;

    public function __construct(ProviderId $id, $name, EmailCollection $notificationEmails)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $name; // TODO Slugify
        $this->notificationEmails = $notificationEmails;
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

    public function getNotificationEmails()
    {
        return $this->notificationEmails;
    }
}
