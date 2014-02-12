<?php

namespace EVT\EvtApplication\Repository;

use EVT\EvtApplication\Entity\Hook;
use Doctrine\ORM\EntityRepository;

class HookRepository extends EntityRepository
{
    public function save(Hook $hook)
    {
        $this->_em->persist($hook);
        $this->_em->flush();
    }
}
