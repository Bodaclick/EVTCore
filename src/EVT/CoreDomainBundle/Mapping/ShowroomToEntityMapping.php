<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomainBundle\Entity\Showroom as ORMShowroom;

/**
 * LeadToEntityMapping
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class ShowroomToEntityMapping
{

    public function map(Showroom $showroom)
    {
        $entity = new ORMShowroom();
        $entity->setSlug($showroom->getSlug());
        return $entity;
    }
}
