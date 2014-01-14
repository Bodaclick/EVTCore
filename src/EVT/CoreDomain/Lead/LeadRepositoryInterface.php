<?php

namespace EVT\CoreDomain\Lead;

use EVT\CoreDomain\RepositoryInterface;

/**
 * LeadRepositoryInterface
 *
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright Bodaclick S.A
 */
interface LeadRepositoryInterface extends RepositoryInterface
{
    public function findByCountry();

    public function findByEventType();
}
