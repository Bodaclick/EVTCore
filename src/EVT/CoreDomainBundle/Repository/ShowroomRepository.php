<?php

namespace EVT\CoreDomainBundle\Repository;

use EVT\CoreDomain\Provider\ShowroomRepositoryInterface as DomainRepository;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
class ShowroomRepository extends EntityRepository implements DomainRepository
{
    public function save($showroom)
    {
    }

    public function delete($showroom)
    {
    }

    public function update($showroom)
    {
    }

    public function findAll()
    {
    }
}
