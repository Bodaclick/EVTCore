<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomainBundle\Entity\Vertical;
use EVT\CoreDomain\Provider\Vertical as DomainVertical;

class VerticalMapping {
    /**
     * map
     *
     * @param EVT\CoreDomain\Vertical\Vertical
     * @return EVT\CoreDomainBundle\Entity\Vertical $vertical
     */
    public function mapDomainToEntity(DomainVertical $vertical)
    {
        $entity = new Vertical();
        $entity->setDomain($vertical->getDomain());
        return $entity;
    }

} 