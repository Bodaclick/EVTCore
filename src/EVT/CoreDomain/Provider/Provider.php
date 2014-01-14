<?php
namespace EVT\CoreDomain\Provider;

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

    public function __construct(ProviderId $id, $name)
    {
        $this->id = $id;
        $this->name = $name;
        $this->slug = $name; // TODO Slugify
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
}
