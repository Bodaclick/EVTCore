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
        return new DomainVertical($vertical->getDomain(), $vertical->getLang(), $vertical->getTimezone());
    }

    /**
     * @param DomainVertical $vertical
     * @return Vertical
     */
    public function mapDomainToEntity($vertical)
    {
        $entityVertical = new Vertical();
        $entityVertical->setDomain($vertical->getDomain());
        $entityVertical->setLang($vertical->getLang());
        $entityVertical->setTimezone($vertical->getTimezone());
        return $entityVertical;
    }
}
