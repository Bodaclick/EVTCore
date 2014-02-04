<?php

namespace EVT\CoreDomainBundle\Mapping;

use EVT\CoreDomainBundle\Entity\Vertical;
use EVT\CoreDomain\Provider\Vertical as DomainVertical;

/**
 * Class VerticalMapping
 * @package EVT\CoreDomainBundle\Mapping
 */
class VerticalMapping implements MappingInterface
{

    /**
     * @param Vertical $vertical
     * @return Vertical
     */
    public function mapEntityToDomain($vertical)
    {
        $domainVertical = new DomainVertical($vertical->getDomain());
        return $domainVertical;
    }

    /**
     * @param DomainVertical $vertical
     * @return Vertical
     */
    public function mapDomainToEntity($vertical)
    {
        $entityVertical = new Vertical();
        $entityVertical->setDomain($vertical->getDomain());
        return $entityVertical;
    }
}
