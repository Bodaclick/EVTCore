<?php

namespace EVT\CoreDomain\Lead;

use EVT\CoreDomain\Provider\Showroom;
use EVT\CoreDomain\RepositoryInterface;

/**
 * LeadRepositoryInterface
 *
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @author    Marco Ferrari <marco.ferrari@bodaclick.com>
 * @copyright 2014 Bodaclick S.A
 */
interface LeadRepositoryInterface extends RepositoryInterface
{
    public function findByCountry();

    public function findByEventType();

    public function findByShowroomEmailSeconds(Showroom $showroom, $email, $seconds);
}
