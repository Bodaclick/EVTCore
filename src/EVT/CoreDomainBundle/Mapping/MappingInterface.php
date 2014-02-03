<?php

namespace EVT\CoreDomainBundle\Mapping;


interface MappingInterface
{
    public function mapEntityToDomain($object);

    public function mapDomainToEntity($object);
}
